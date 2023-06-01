<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Actions\StoreOrderAction;
use App\Domain\Order\Actions\StoreOrderHasProductAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use App\Domain\Order\Traits\CheckStock;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Client\Order\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    use CheckStock;

    public function index(): Response
    {
        return Inertia::render('Order/Index', [
            'orders' => Order::query()
                -> select(
                        'id',
                        'purchase_date',
                        'payment_status',
                        'purchase_total',
                        'url',
                    )
                -> whereAuthUser()
                -> orderByDesc('purchase_date')
                -> paginate(10),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Order/Create', [
            'limitatedStock' => session('limitatedStock'),
            'success' => session('success'),
        ]);
    }

    public function store(
        StoreRequest $request,
        StoreOrderAction $store_order_action,
        StoreOrderHasProductAction $store_order_has_product_action,
        PlaceToPayPaymentServices $placetopay): RedirectResponse
    {
        $data = StoreOrderData::fromRequest($request);

        $limitated_stock = $this->solvent_order($data);

        if (count($limitated_stock) == 0) {
            $order = $store_order_action->handle($data);
            $store_order_has_product_action->handle($data, $order);
            $placetopay->pay($order, $request->ip(), $request->userAgent());
        } else {
            return Redirect::route('order.create')
                ->with('success', 'Order rejected.')
                ->with('limitatedStock', json_encode($limitated_stock));
        }

        return Redirect::route('order.index')->with('success', 'Order created.');
    }

    public function show(string $id): Response
    {
        return Inertia::render('Order/Show', [
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
