<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

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

        if ($status == 'APPROVED') {
            $this->order->paid();
            $this->paidLog();
        } elseif ($status == 'PENDING') {
            if ($message == 'La petición se encuentra pendiente') {
                $this->order->waiting();
            } else {
                $this->order->pending();
            }
            $this->pendingLog();
        } elseif ($status == 'REJECTED') {
            $this->order->canceled();
            $this->canceledLog();
        }
    }

    private function paidLog(): void
    {
        Log::channel('payment_webcheckout')
            ->info('['.$this->response->status().']['.$this->mode.'][APPROVED] Payment reported successfully for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function pendingLog(): void
    {
        Log::channel('payment_webcheckout')
            ->warning('['.$this->response->status().']['.$this->mode.'][PENDING] Payment is stil pending for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function canceledLog(): void
    {
        Log::channel('payment_webcheckout')
            ->error('['.$this->response->status().']['.$this->mode.'][REJECTED] Payment has been rejected for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }
}
