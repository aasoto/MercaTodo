<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderGetLastAction
{
    public static function handle(): Model
    {
        return Order::query()->where('user_id', auth()->user()->id)
            ->where('payment_status', '=', 'pending')->latest()->first();
    }
}
