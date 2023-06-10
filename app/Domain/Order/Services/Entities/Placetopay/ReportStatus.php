<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Actions\UpdateOrderAction;
use App\Domain\Order\Dtos\UpdateOrderData;
use App\Domain\Order\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class ReportStatus
{
    public function __construct(
        public Order $order,
        public Response $response,
        public string $mode = 'MANUAL',
    )
    {}

    public function saveOk(): void
    {
        $status = $this->response->json()['status']['status'];
        $message = $this->response->json()['status']['message'];

        $logs_report_status = new LogsReportStatus($this->order, $this->response, $this->mode);

        if ($status == 'APPROVED') {
            $this->order->paid();

            (new UpdateOrderAction)->handle(
                UpdateOrderData::fromResult(
                    $this->response->json()['status']['date'],
                    $this->response->json()['payment'][0]['amount']['from']['total'],
                ),
                $this->response->json()['payment'][0]['reference']
            );

            $logs_report_status->save('APPROVED');

        } elseif ($status == 'PENDING') {
            if ($message == 'La petición se encuentra pendiente') {

                $payment_status = $this->response->json()['payment'][0]['status']['status'];
                $payment_message = $this->response->json()['payment'][0]['status']['message'];

                if (
                    $payment_status == 'REJECTED' &&
                    $payment_message == 'Transacción pendiente. Por favor consulte con su entidad financiera si el débito fue realizado'
                ) {
                    $this->order->verify_bank();
                } else {
                    $this->order->waiting();
                }

            } else {
                $this->order->pending();
            }
            $logs_report_status->save('PENDING');
        } elseif ($status == 'REJECTED') {
            $this->order->canceled();
            $logs_report_status->save('REJECTED');
        } else {
            $logs_report_status->save($this->response->json()['status']['status']);
        }
    }


}
