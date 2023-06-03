<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Actions\GetOrderAction;
use App\Domain\Order\Actions\OrderUpdateAction;
use App\Domain\Order\Services\Entities\Placetopay\Authentication;
use App\Domain\Order\Services\Entities\Placetopay\Buyer;
use App\Domain\Order\Services\Entities\Placetopay\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaceToPayPaymentServices
{
    private string $ipAddress, $userAgent;

    public function pay(Model $order, string $ipAddress, string $userAgent)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;

        $response = Http::post(config('placetopay.url').config('placetopay.route.api'),
            $this->createSession($order)
        );

        if ($response->ok()) {
            $order->request_id = $response->json()['requestId'];
            $order->url = $response->json()['processUrl'];

            OrderUpdateAction::handle($order);

            Log::channel('response_webcheckout')
                ->info('['.$response->status().'] Session created successfully for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                    'response' => json_decode($response->body(), true),
                ]);

            // redirect()->to($order->url)->send();
        } else {
            Log::channel('response_webcheckout')
                ->error('['.$response->status().'] Error creating the session for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                    'response' => json_decode($response->body(), true),
                ]);
        }

        // throw new \Exception($response->body());


    }

    private function createSession(Model $order): array
    {
        $authentication = new Authentication();
        $buyer = new Buyer(auth()->user());
        $payment = new Payment($order);

        return [
            'auth' => $authentication->getAuth(),
            'buyer' => $buyer->getBuyer(),
            'payment' => $payment->getPayment(),
            'expiration' => Carbon::now()->addDay(),
            'returnUrl' => route('payment.response', $order->code),
            'ipAddress' => $this->ipAddress,
            'userAgent' => $this->userAgent,
        ];
    }

    public function getRequestInformation(string $code): string
    {
        $order = GetOrderAction::handle($code);
        $authentication = new Authentication();

        $result = Http::post(config('placetopay.url').config('placetopay.route.api').$order->request_id, [
            'auth' => $authentication->getAuth()
        ]);


        if ($result->ok()) {
            $status = $result->json()['status']['status'];
            if ($status == 'APPROVED') {
                $order->paid();
                Log::channel('payment_webcheckout')
                    ->info('['.$result->status().'][MANUAL][APPROVED] Payment reported successfully for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                        'response' => json_decode($result->body(), true),
                    ]);

            } elseif ($status == 'PENDING') {
                $order->pending();
                Log::channel('payment_webcheckout')
                    ->warning('['.$result->status().'][MANUAL][PENDING] Payment is stil pending for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                        'response' => json_decode($result->body(), true),
                    ]);

            } elseif ($status == 'REJECTED') {
                $order->canceled();
                Log::channel('payment_webcheckout')
                    ->error('['.$result->status().'][MANUAL][REJECTED] Payment has been rejected for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                        'response' => json_decode($result->body(), true),
                    ]);

            }

            return 'ok';
        }

        Log::channel('payment_webcheckout')
            ->critical('['.$result->status().'] Error for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                'response' => json_decode($result->body(), true),
            ]);
        throw  new \Exception($result->body());
    }
}
