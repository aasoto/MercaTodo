<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Traits\TranKey;
use Illuminate\Support\Str;

class Authentication
{
    use TranKey;

    /**
     * @return array<mixed>
     */
    public function getAuth(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => config('placetopay.login'),
            'tranKey' => $this->getTranKey($nonce, $seed),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }
}
