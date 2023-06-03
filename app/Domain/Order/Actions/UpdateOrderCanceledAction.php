<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;

class UpdateOrderCanceledAction
{
    public function handle(string $code): void
    {
        Order::where('code', $code)->first()->canceled();
    }
}
