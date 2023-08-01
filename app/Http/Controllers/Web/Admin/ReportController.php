<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Exports\OrdersReport;
use App\Http\Exports\ProductsReport;
use App\Http\Exports\UsersReport;
use App\Http\Jobs\ReportJob;
use App\Http\Requests\Web\Admin\Order\ReportRequest as OrderReportRequest;
use App\Http\Requests\Web\Admin\Product\ReportRequest as ProductReportRequest;
use App\Http\Requests\Web\Admin\User\ReportRequest as UserReportRequest;
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

    public function createOrder(Request $request): Response
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

    public function exportOrder(OrderReportRequest $request): RedirectResponse
    {
        $path_file = 'reports/orders/orders_'.$request->validated()['time'].'.xlsx';
        (new OrdersReport($request->validated()))->queue($path_file)->chain([
            new ReportJob("Order's report", $request->user(), $path_file),
        ]);

        return Redirect::route('order.report.create')->with('success', 'Orders report generated.');
    }

    public function createProduct(Request $request): Response
    {
        return Inertia::render('Report/Product/Create', [
            'filters' => $request->only([
                'search', 'category', 'minStock', 'maxStock', 'minPrice', 'maxPrice', 'unitCode', 'availability', 'soldOut'
            ]),
            'products' => Product::query()
                -> whereSearch($request->input('search'))
                -> whereCategory($request->input('category'))
                -> whereUnit($request->input('unitCode'))
                -> whenAvailability($request->input('availability'))
                -> whereSoldOut($request->input('soldOut'))
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
                -> orderBy('products.id')
                -> paginate(10)
                -> withQueryString(),
            'productsCategories' => ProductCategory::getFromCache(),
            'units' => Unit::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function exportProduct(ProductReportRequest $request): RedirectResponse
    {
        $path_file = 'reports/products/products_'.$request->validated()['time'].'.xlsx';
        (new ProductsReport($request->validated()))->queue($path_file)->chain([
            new ReportJob("Product's report", $request->user(), $path_file),
        ]);

        return Redirect::route('product.report.create')->with('success', 'Products report generated.');
    }

    public function createUser(Request $request): Response
    {
        return Inertia::render('Report/User/Create', [
            'filters' => $request->only([
                'search', 'typeDocument', 'verified', 'enabled', 'role', 'date1', 'date2', 'stateId', 'cityId'
            ]),
            'users' => User::query()
                -> whereSearch($request->input('search'))
                -> whereTypeDocument($request->input('typeDocument'))
                -> whereEmailVerified($request->input('verified'))
                -> whereEnabled($request->input('enabled'))
                -> whereRole($request->input('role'))
                -> whereBetweenCreatedAt($request->input('date1'), $request->input('date2'))
                -> whereStateAndCity($request->input('stateId'), $request->input('cityId'))
                -> select(
                    'users.id',
                    'users.type_document',
                    'users.number_document',
                    'users.first_name',
                    'users.second_name',
                    'users.surname',
                    'users.second_surname',
                    'users.email_verified_at',
                    'users.enabled',
                    'states.name as state_name',
                    'cities.name as city_name',
                    'model_has_roles.role_id',
                    'users.created_at',
                )
                -> join('states', 'users.state_id', 'states.id')
                -> join('cities', 'users.city_id', 'cities.id')
                -> join('type_documents', 'users.type_document', 'type_documents.code')
                -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                -> orderBy('users.id')
                -> paginate(10)
                -> withQueryString(),
            'states' => State::getFromCache(),
            'cities' => City::getFromCache(),
            'typeDocuments' => TypeDocument::getFromCache(),
            'roles' => Role::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function exportUser(UserReportRequest $request): RedirectResponse
    {
        $path_file = 'reports/users/users_'.$request->validated()['time'].'.xlsx';
        (new UsersReport($request->validated()))->queue($path_file)->chain([
            new ReportJob("Users' report", $request->user(), $path_file),
        ]);

        return Redirect::route('user.report.create')->with('success', 'Users report generated.');
    }
}
