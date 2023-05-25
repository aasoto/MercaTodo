<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;

class StoreOrderAction
{
    public function handle(StoreOrderData $data): Model
    {
        $purchase_total = 0;

        foreach ($data->products as $key => $value) {
            $purchase_total = $purchase_total + $value['totalPrice'];
        }
        return Order::create([
            'user_id' => auth()->user()?->id,
            'purchase_date' => date('Y-m-d'),
            'payment_status' => 'pending',
            'purchase_total' => $purchase_total,
        ]);
    }
}
