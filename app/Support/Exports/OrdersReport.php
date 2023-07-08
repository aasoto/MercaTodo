<?php

namespace App\Support\Exports;

use App\Domain\Order\Models\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class OrdersReport implements FromQuery
{
    use Exportable;

    public function query()
    {
        return Order::query();
    }
}
