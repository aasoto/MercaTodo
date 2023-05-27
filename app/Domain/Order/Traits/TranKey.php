<?php

namespace App\Domain\Order\Traits;

trait TranKey {

    public function getTranKey($nonce, $seed): string
    {
        return base64_encode(sha1($nonce . $seed . env('WEBCHECKOUT_SECRETKEY'), true));
    }

}
