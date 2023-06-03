<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Actions\GetOrderAction;
use App\Domain\Order\Actions\OrderUpdateAction;
use App\Domain\Order\Services\Entities\Placetopay\Authentication;
use App\Domain\Order\Services\Entities\Placetopay\Buyer;
use App\Domain\Order\Services\Entities\Placetopay\Payment;
use App\Domain\Order\Services\Entities\Placetopay\ReportStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class PlaceToPayPaymentServices
{
    private string $ipAddress, $userAgent;

    public function pay(Model $order, string $ipAddress, string $userAgent)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;

        try {
            $response = Http::post(config('placetopay.url').config('placetopay.route.api'),
                $this->createSession($order)
            );
            Log::channel('create_link_webcheckout')->info('Payment link created successfully in order No.'.$order->id.' with code '.$order->code.' {response} ', [
                'response' => json_decode($response, true),
            ]);
        } catch (\Throwable $th) {
            Log::channel('create_link_webcheckout')->critical('Can not create new payment link for placetopay in order No. '.$order->id.' with code '.$order->code.' {Throwable}: ', [
                'Throwable' => json_decode($th, true),
            ]);
        }

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

            return Redirect::route('payment.error');
        }

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
            'cancelUrl' => route('payment.canceled', $order->code),
            'ipAddress' => $this->ipAddress,
            'userAgent' => $this->userAgent,
        ];
    }

    public function getRequestInformation(string $code): string
    {
        $order = GetOrderAction::handle($code);
        $authentication = new Authentication();

        $response = Http::post(config('placetopay.url').config('placetopay.route.api').$order->request_id, [
            'auth' => $authentication->getAuth()
        ]);

        $status = new ReportStatus($order, $response);
        if ($response->ok()) {
            $status->saveOk();
            return 'ok';
        } else {
            Log::channel('payment_webcheckout')
                ->critical('['.$response->status().'] Error for the order No.'.$order->id.' with code '.$order->code.' with the response {response}', [
                    'response' => json_decode($response->body(), true),
                ]);
            return 'error';
        }

    }
}
