<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Actions\OrderGetLastAction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PlaceToPayPaymentServices
{
    private function createSession(Model $order, string $ipAddress, string $userAgent): array
    {
        return [
            'auth' => $this->getAuth(),
            'buyer' => [
                'name' => auth()->user()->first_name.' '.auth()->user()->surname,
                'email' => auth()->user()->email
            ],
            'payment' => [
                'reference' => $order->id,
                'description' => 'Payment of purchase total',
                'amount' => [
                    'currency' => 'COP',
                    'total' => $order->purchase_total
                ]
            ],
            'expiration' => Carbon::now()->addHour(),
            'returnUrl' => route('payments.processResponse'),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
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

    public function getRequestInformation(): string
    {
        $order = OrderGetLastAction::handle();

        $result = Http::post(config('placetopay.url')."/api/session/$order->order_id", [
            'auth' => $this->getAuth()
        ]);

        if ($result->ok()) {
            $status = $result->json()['status']['status'];
            if ($status == 'APPROVED') {
                $order->paid();
            } elseif ($status == 'REJECTED') {
                $order->pending();
            }

            return 'ok';
        }

        throw  new \Exception($result->body());
    }
}
