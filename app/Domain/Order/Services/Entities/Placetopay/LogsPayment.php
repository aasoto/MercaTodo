<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class LogsPayment
{
    public function __construct(
        private Response $response,
        private Order $order
    )
    {}

    public function save(): void
    {
        if ($this->response->ok()) {
            $this->ok();
        } elseif ($this->response->unauthorized()) {
            $this->unauthorized();
        } elseif ($this->response->status() === 500) {
            $this->error();
        } else {
            $this->default_response();
        }
    }

    private function ok(): void
    {
        Log::channel('response_webcheckout')
            ->info('['.$this->response->status().'][SUCCESS] Session created successfully for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function unauthorized(): void
    {
        Log::channel('response_webcheckout')
            ->error('['.$this->response->status().'][UNAUTHORIZED] Error creating the session for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function error(): void
    {
        Log::channel('response_webcheckout')
            ->error('['.$this->response->status().'][INTERNAL SERVER ERROR] Error creating the session for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }

    private function default_response(): void
    {
        Log::channel('response_webcheckout')
            ->error('['.$this->response->status().'][OTHER RESPONSE] Error creating the session for the order No.'.$this->order->id.' with code '.$this->order->code.' with the response {response}', [
                'response' => json_decode($this->response->body(), true),
            ]);
    }
}
