<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;

class GetOrderAction
{
    public static function handle(string $code): Model|null
    {
        return Order::query()->where('code', $code)->first();
    }
}
