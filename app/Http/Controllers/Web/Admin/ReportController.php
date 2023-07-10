<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Exports\OrdersReport;
use App\Http\Exports\ProductsReport;
use App\Http\Jobs\OrdersReportJob;
use App\Http\Jobs\ReportJob;
use App\Http\Requests\Web\Admin\Order\ReportRequest as OrderReportRequest;
use App\Http\Requests\Web\Admin\Product\ReportRequest as ProductReportRequest;
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

    public function export_order(OrderReportRequest $request): RedirectResponse
    {
        $path_file = 'reports/orders/orders_'.time().'.xlsx';
        (new OrdersReport($request->validated()))->queue($path_file)->chain([
            new ReportJob("Order's report", $request->user(), $path_file),
        ]);

        return Redirect::route('order.report.create')->with('success', 'Orders report generated.');
    }

    public function create_product(Request $request): Response
    {
        return Inertia::render('Report/Product/Create', [
            'filters' => $request->only([
                'search', 'category', 'minStock', 'maxStock', 'minPrice', 'maxPrice', 'unitCode', 'availability'
            ]),
            'products' => Product::query()
                -> whereSearch($request->input('search'))
                -> whereCategory($request->input('category'))
                -> whereUnit($request->input('unitCode'))
                -> whenAvailability($request->input('availability'))
                -> whereStockBetween($request->input('minStock'), $request->input('maxStock'))
                -> wherePriceBetween($request->input('minPrice'), $request->input('maxPrice'))
                -> select(
                    'products.name',
                    'products_categories.name as category',
                    'products.price',
                    'units.name as unit',
                    'products.stock',
                    'products.availability',
                )
                -> join('products_categories', 'products.products_category_id', 'products_categories.id')
                -> join('units', 'products.unit', 'units.code')
                -> orderby('products.id')
                -> paginate(10)
                -> withQueryString(),
            'productsCategories' => ProductCategory::getFromCache(),
            'units' => Unit::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function export_product(ProductReportRequest $request): RedirectResponse
    {
        $path_file = 'reports/orders/orders_'.time().'.xlsx';
        (new ProductsReport($request->validated()))->queue($path_file)->chain([
            new ReportJob("Product's report", $request->user(), $path_file),
        ]);

        return Redirect::route('product.report.create')->with('success', 'Products report generated.');
    }
}
