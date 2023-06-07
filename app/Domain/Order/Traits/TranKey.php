<?php

namespace App\Domain\Order\Traits;

trait TranKey {

    public function getTranKey(string $nonce, string $seed): string
    {
        return base64_encode(
            hash(
                'sha256',
                $nonce.$seed.config('placetopay.tranKey'),
                true
            )
        );
    }

}
