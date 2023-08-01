<?php

namespace App\Domain\Order\Traits;

trait CodeOrder
{
    public function generateCode(): string
    {
        return bin2hex(random_bytes(8));
    }
}
