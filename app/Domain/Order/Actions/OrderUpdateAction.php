<?php

namespace App\Domain\Order\Actions;

use Illuminate\Database\Eloquent\Model;

class OrderUpdateAction
{
    public static function handle(Model $order): void
    {
        $order->save();
    }
}
