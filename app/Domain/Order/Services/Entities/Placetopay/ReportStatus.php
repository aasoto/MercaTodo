<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class ReportStatus
{
    public function __construct(
        public Model $order,
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
            $logs_report_status->save('APPROVED');
        } elseif ($status == 'PENDING') {
            if ($message == 'La petición se encuentra pendiente') {
                $this->order->waiting();
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
