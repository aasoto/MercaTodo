<?php

namespace App\Http\Exports;

use App\Domain\Order\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersReport implements FromView
{
    use Exportable;

    /**
     * @param array<mixed> $filters
     */
    public function __construct(
        private array $filters,
    )
    {}

    public function view(): View
    {
        return view('report.orders', [
            'orders' => Order::query()
            ->whereDateBetween(
                isset($this->filters['date_1']) ? $this->filters['date_1'] : null,
                isset($this->filters['date_2']) ? $this->filters['date_2'] : null,
                )
            -> whereUserNumberDocument(isset($this->filters['number_document']) ? $this->filters['number_document'] : null)
            -> wherePaymentStatus(isset($this->filters['payment_status']) ? $this->filters['payment_status'] : null)
            -> wherePurchaseTotal(
                isset($this->filters['min_total']) ? $this->filters['min_total'] : null,
                isset($this->filters['max_total']) ? $this->filters['max_total'] : null,
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
                'users.number_document',
                'users.first_name',
                'users.second_name',
                'users.surname',
                'users.second_surname',
            )
            -> orderByDesc('orders.purchase_date')
            -> join('users', 'orders.user_id', 'users.id')
            -> get(),
        ]);
    }
}
