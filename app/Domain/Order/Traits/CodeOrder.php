<?php

namespace App\Domain\Order\Traits;

/**
 *
 */
trait CodeOrder
{
    public function generate_code(): string
    {
        return bin2hex(random_bytes(8));
    }
}
