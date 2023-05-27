<?php

namespace App\Domain\Order\Traits;

trait DateISO8601 {

    public function getDate(): string
    {
        return date('c');
    }

    public function getExpirationDate($seed): string
    {
        return date('c', strtotime($seed.'+ 1 days'));
    }
}
