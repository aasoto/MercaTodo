<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class LogsReportStatus
{
    public function __construct(
        private Order $order,
        private Response $response,
        private string $mode = 'MANUAL',
    )
    {}

    public function save(string $status): void
    {
        switch ($status) {
            case 'APPROVED':
                $this->paid();
                break;
            case 'PENDING':
                $this->pending();
                break;
            case 'REJECTED':
                $this->canceled();
                break;
            case 'APPROVED_PARTIAL':
                $this->approvedPartial();
                break;
            case 'PARTIAL_EXPIRED':
                $this->partialExpired();
                break;
            default:
                $this->defaultStatus($this->response->json()['status']['status']);
                break;
        }
    }

    private function paid(): void
    {
        Log::channel('payment_webcheckout')
            ->info('['.$this->response->status().']['.$this->mode.'][APPROVED] Payment reported successfully for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function pending(): void
    {
        Log::channel('payment_webcheckout')
            ->warning('['.$this->response->status().']['.$this->mode.'][PENDING] Payment is stil pending for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function canceled(): void
    {
        Log::channel('payment_webcheckout')
            ->error('['.$this->response->status().']['.$this->mode.'][REJECTED] Payment has been rejected for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function approvedPartial(): void
    {
        Log::channel('payment_webcheckout')
            ->warning('['.$this->response->status().']['.$this->mode.'][APPROVED_PARTIAL] for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function partialExpired(): void
    {
        Log::channel('payment_webcheckout')
            ->error('['.$this->response->status().']['.$this->mode.'][PARTIAL_EXPIRED] for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function defaultStatus(string $status): void
    {
        Log::channel('payment_webcheckout')
            ->notice('['.$this->response->status().']['.$this->mode.']['.$status.'] Payment has an unknown response for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }
}
