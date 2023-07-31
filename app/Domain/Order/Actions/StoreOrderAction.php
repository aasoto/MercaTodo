<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Traits\CodeOrder;
use Illuminate\Database\Eloquent\Model;

class StoreOrderAction
{
    use CodeOrder;

    public function handle(StoreOrderData $data): Model
    {
        $purchase_total = 0;

        foreach ($data->products as $key => $value) {
            $purchase_total = $purchase_total + $value['totalPrice'];
        }

        return Order::create([
            'code' => $this->generateCode(),
            'user_id' => auth()->user()?->id,
            'purchase_date' => date('Y-m-d H:i:s'),
            'payment_status' => 'pending',
            'purchase_total' => $purchase_total,
        ]);
    }
}
