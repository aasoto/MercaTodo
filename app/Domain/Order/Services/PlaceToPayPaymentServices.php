<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Actions\GetOrderAction;
use App\Domain\Order\Actions\OrderUpdateAction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlaceToPayPaymentServices
{
    private string $ipAddress, $userAgent;

    public function pay(Model $order, string $ipAddress, string $userAgent)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;

        $response = Http::post(config('placetopay.url').'/api/session',
            $this->createSession($order)
        );

        if ($response->ok()) {
            $order->request_id = $response->json()['requestId'];
            $order->url = $response->json()['processUrl'];

            OrderUpdateAction::handle($order);

            Log::channel('response_webcheckout')
                ->info('Session created successfully for the order No.{id} with code {code} with the response {response}', [
                    'id' => $order->id,
                    'code' => $order->code,
                    'response' => json_decode($response->body(), true),
                ]);

            // redirect()->to($order->url)->send();
        } else {
            Log::channel('response_webcheckout')
                ->error('Error creating the session for the order No.{id} with code {code} with the response {response}', [
                    'id' => $order->id,
                    'code' => $order->code,
                    'response' => json_decode($response->body(), true),
                ]);
        }

        // throw new \Exception($response->body());


    }

    private function createSession(Model $order): array
    {
        $user = auth()->user();

        return [
            'auth' => $this->getAuth(),
            'buyer' => [
                'document' => $user->number_document,
                'name' => $user->first_name.' '.$user->second_name,
                'surname' => $user->surname.' '.$user->second_surname,
                'mobile' => $user->phone,
                'email' => $user->email,
                'address' => $user->address,
            ],
            'payment' => [
                'reference' => $order->code,
                'description' => 'Payment of purchase total',
                'amount' => [
                    'currency' => 'COP',
                    'total' => $order->purchase_total
                ]
            ],
            'expiration' => Carbon::now()->addDay(),
            'returnUrl' => route('payment.response', $order->code),
            'ipAddress' => $this->ipAddress,
            'userAgent' => $this->userAgent,
        ];
    }

    private function getAuth(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => config('placetopay.login'),
            'tranKey' => base64_encode(
                hash(
                    'sha256',
                    $nonce.$seed.config('placetopay.tranKey'),
                    true
                )
            ),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }

    public function getRequestInformation(string $code): string
    {
        $order = GetOrderAction::handle($code);

        $result = Http::post(config('placetopay.url')."/api/session/$order->request_id", [
            'auth' => $this->getAuth()
        ]);


        if ($result->ok()) {
            $status = $result->json()['status']['status'];
            if ($status == 'APPROVED') {
                $order->paid();
                Log::channel('payment_webcheckout')
                    ->info('Payment reported successfully for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                        'response' => json_decode($result->body(), true),
                    ]);

            } elseif ($status == 'REJECTED') {
                $order->pending();
                Log::channel('payment_webcheckout')
                    ->warning('Payment has been rejected for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                        'response' => json_decode($result->body(), true),
                    ]);
            }

            return 'ok';
        }

        Log::channel('payment_webcheckout')
                    ->error('Error for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                        'response' => json_decode($result->body(), true),
                    ]);
        throw  new \Exception($result->body());
    }
}
