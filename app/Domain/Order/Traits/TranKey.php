<?php

namespace App\Domain\Order\Traits;

trait TranKey {

    public function getTranKey($nonce, $seed): string
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
