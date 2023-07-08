<?php

namespace App\Support\Exports;

use App\Domain\Order\Models\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class OrdersReport implements FromQuery
{
    use Exportable;

    public function __construct(
        private array $filters,
    )
    {}

    public function query()
    {
        return Order::query()
            ->whereDateBetween(
                isset($this->filters['date_1']) ? $this->filters['date_1'] : null,
                isset($this->filters['date_2']) ? $this->filters['date_2'] : null,
                )
            -> select(
                'orders.id',
                'orders.code',
                'orders.purchase_date',
                'orders.payment_date',
                'orders.payment_status',
                'orders.purchase_total',
                'orders.url',
                'orders.updated_at',
                'users.first_name',
                'users.second_name',
                'users.surname',
                'users.second_surname',
            )
            -> orderByDesc('orders.purchase_date')
            -> join('users', 'orders.user_id', 'users.id');
    }
}
