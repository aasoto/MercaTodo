<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Actions\StoreOrderAction;
use App\Domain\Order\Actions\StoreOrderHasProductAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Client\Order\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Order/List', [
            'orders' => Order::query()
                -> select(
                        'id',
                        'purchase_date',
                        'payment_status',
                        'purchase_total',
                    )
                -> whereAuthUser()
                -> orderByDesc('purchase_date')
                -> paginate(10),
            'success' => session('success'),
        ]);
    }

    public function store(
        StoreRequest $request,
        StoreOrderAction $store_order_action,
        StoreOrderHasProductAction $store_order_has_product_action): RedirectResponse
    {
        $data = StoreOrderData::fromRequest($request);

        $store_order_has_product_action->handle($data, $store_order_action->handle($data));

        return Redirect::route('order.index')->with('success', 'Order created.');
    }

    public function show(string $id): Response
    {
        return Inertia::render('Order/Detail', [
            'order' => Order::query()
                -> select(
                        'id',
                        'purchase_date',
                        'payment_status',
                        'purchase_total',
                    )
                -> where('id', $id)
                -> first(),
            'products' => OrderHasProduct::query()
                -> select(
                        'products.name',
                        'order_has_products.price',
                        'order_has_products.quantity',
                        'units.name as unit',
                    )
                -> join('products', 'order_has_products.product_id', 'products.id')
                -> join('units', 'products.unit', 'units.code')
                -> whereMatchOrder($id)
                -> paginate(10),
        ]);
    }
}
