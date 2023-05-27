<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Traits\DateISO8601;
use App\Domain\Order\Traits\Nonce;
use App\Domain\Order\Traits\TranKey;

class WebCheckoutServices
{
    use DateISO8601, Nonce, TranKey;

    public function createSession($purchase_total)
    {
        $user = auth()->user();
        $nonce = $this->getNonce();
        $seed = $this->getDate();

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('WEBCHECKOUT_URL')."/api/session",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'locale' => 'es_CO',
                'auth' => [
                    'login' => env('WEBCHECKOUT_LOGIN'),
                    'tranKey' => $this->getTranKey($nonce, $seed),
                    'nonce' => $nonce,
                    'seed' => $seed
                ],
                'buyer' => [
                    'document' => $user->number_document,
                    'documentType' => $user->type_document,
                    'name' => $user->first_name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'mobile' => $user->phone
                ],
                'payment' => [
                    'reference' => time(),
                    'description' => 'Prueba de pago',
                    'amount' => [
                        'currency' => 'COP',
                        'total' => $purchase_total
                    ],
                ],
                'paymentMethod' => null,
                'expiration' => $this->getExpirationDate($seed),
                'returnUrl' => 'http://127.0.0.1:8000/showcase',
                'cancelUrl' => 'http://127.0.0.1:8000/showcase',
                'ipAddress' => $_SERVER['REMOTE_ADDR'],
                'userAgent' => $_SERVER['HTTP_USER_AGENT']
            ]),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }
}
