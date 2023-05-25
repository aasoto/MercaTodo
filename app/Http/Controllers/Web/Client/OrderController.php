<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
                    )
                -> whereAuthUser()
                -> orderBy('purchase_date')
                -> paginate(10),
        ]);
    }

    public function show(string $id): Response
    {
        return Inertia::render('Order/Detail', [
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
                -> get()
        ]);
    }
}
