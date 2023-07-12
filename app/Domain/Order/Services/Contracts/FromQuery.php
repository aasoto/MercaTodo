<?php

namespace App\Domain\Order\Services\Contracts;

use App\Domain\Order\Models\Order;

interface FromQuery
{
    /**
     * @return Order
     */
    public function query();
}
