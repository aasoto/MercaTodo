<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Jobs\OrdersReportJob;
use App\Http\Requests\Web\Admin\Order\ReportRequest;
use App\Support\Exports\OrdersReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Report/Index', [
            'success' => session('success'),
        ]);
    }

    public function create_order(Request $request): Response
    {
        return Inertia::render('Report/Order/Create', [
            'filters' => $request->only([
                'date1', 'date2', 'numberDocument', 'paymentStatus', 'minTotal', 'maxTotal'
            ]),
            'orders' => Order::query()
                -> whereDateBetween($request->input('date1'), $request->input('date2'))
                -> whereUserNumberDocument($request->input('numberDocument'))
                -> wherePaymentStatus($request->input('paymentStatus'))
                -> wherePurchaseTotal($request->input('minTotal'), $request->input('maxTotal'))
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
                -> join('users', 'orders.user_id', 'users.id')
                -> paginate(10)
                -> withQueryString(),
            'success' => session('success'),
        ]);
    }

    public function export_order(ReportRequest $request): RedirectResponse
    {
        $path_file = 'reports/orders/orders_'.time().'.xlsx';
        (new OrdersReport($request->validated()))->queue($path_file)->chain([
            new OrdersReportJob($request->user(), $path_file),
        ]);

        return Redirect::route('order.report.create')->with('success', 'Orders report generated.');
    }
}
