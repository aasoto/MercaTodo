<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Dtos\UpdateOrderData;
use App\Domain\Order\Models\Order;

class UpdateOrderAction
{
    public function handle(UpdateOrderData $data, string $code): void
    {
        Order::where('code', $code)->update([
            'payment_date' => $data->payment_date,
            'purchase_total' => $data->purchase_total,
        ]);
    }
}
