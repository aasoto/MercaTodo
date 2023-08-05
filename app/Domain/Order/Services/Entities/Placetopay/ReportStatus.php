<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Actions\UpdateOrderAction;
use App\Domain\Order\Dtos\UpdateOrderData;
use App\Domain\Order\Models\Order;
use Illuminate\Http\Client\Response;

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

        $logs_report_status = new LogsReportStatus($this->order, $this->response, $this->mode);

        switch ($status) {
            case 'APPROVED':
                $this->approved($logs_report_status);
                break;
            case 'PENDING':
                $this->pending($logs_report_status);
                break;
            case 'REJECTED':
                $this->rejected($logs_report_status);
                break;
            case 'APPROVED_PARTIAL':
                $this->approvedPartial($logs_report_status);
                break;
            case 'PARTIAL_EXPIRED':
                $this->partialExpired($logs_report_status);
                break;
            default:
                $this->other($logs_report_status);
                break;
        }
    }


    private function approved(LogsReportStatus $logs_report_status): void
    {
        $this->order->paid();

        (new UpdateOrderAction)->handle(
            UpdateOrderData::fromResult(
                $this->response->json()['status']['date'],
                $this->response->json()['payment'][0]['amount']['from']['total'],
            ),
            $this->response->json()['payment'][0]['reference']
        );

        $logs_report_status->save('APPROVED');
    }

    private function pending(LogsReportStatus $logs_report_status): void
    {
        $message = $this->response->json()['status']['message'];

        if ($message == 'La petición se encuentra pendiente') {

            $payment_status = $this->response->json()['payment'][0]['status']['status'];
            $payment_message = $this->response->json()['payment'][0]['status']['message'];

            if (
                $payment_status == 'REJECTED' &&
                $payment_message == 'Transacción pendiente. Por favor consulte con su entidad financiera si el débito fue realizado'
            ) {
                $this->order->verifyBank();
            } else {
                $this->order->waiting();
            }

        } else {
            $this->order->pending();
        }
        $logs_report_status->save('PENDING');
    }

    private function rejected(LogsReportStatus $logs_report_status): void
    {
        $this->order->canceled();
        $logs_report_status->save('REJECTED');
    }

    private function approvedPartial(LogsReportStatus $logs_report_status): void
    {
        $this->order->approvedPartial();
        $logs_report_status->save('APPROVED_PARTIAL');
    }

    private function partialExpired(LogsReportStatus $logs_report_status): void
    {
        $this->order->partialExpired();
        $logs_report_status->save('PARTIAL_EXPIRED');
    }

    private function other(LogsReportStatus $logs_report_status): void
    {
        $logs_report_status->save($this->response->json()['status']['status']);
    }
}
