<?php

namespace App\Http\Controllers\Api\Client;

use App\Domain\Order\Actions\StoreOrderAction;
use App\Domain\Order\Actions\StoreOrderHasProductAction;
use App\Domain\Order\Dtos\IndexOrderData;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use App\Domain\Order\Traits\CheckStock;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Order\IndexRequest;
use App\Http\Requests\Api\Client\Order\StoreRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    use CheckStock;

    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $data = IndexOrderData::fromRequest($request);

        $orders = Order::query()
            ->orderByDesc('purchase_date')
            ->where('user_id', $data->user_id)
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function store(
        StoreRequest $request,
        StoreOrderAction $store_order_action,
        StoreOrderHasProductAction $store_order_has_product_action,
        PlaceToPayPaymentServices $placetopay): JsonResponse
    {
        $data = StoreOrderData::fromRequest($request);

        $limitated_stock = $this->solventOrder($data);

        if (count($limitated_stock) == 0) {
            /**
             * @var Order $order
             */
            $order = $store_order_action->handle($data);
            $store_order_has_product_action->handle($data, $order);
            $placetopay->pay(
                $order,
                $data,
                $request->ip() ? $request->ip() : '',
                $request->userAgent() ? $request->userAgent() : ''
            );
        } else {
            return response()->json([
                'message' => trans('Order rejected'),
                'limitatedStock' => $limitated_stock,
            ], 400);
        }

        return response()->json([
            'message' => trans('Order created'),
            'orderId' => $order->id,
        ], 201);
    }

    public function show(string $code): JsonResponse
    {
        /**
         * @var Order $order
         */
        $order = Order::query()->where('code', $code)->first();
        if (isset($order->id)) {
            $products_by_order = OrderHasProduct::query()
                -> select(
                        'products.name',
                        'order_has_products.price',
                        'order_has_products.quantity',
                        'units.name as unit',
                    )
                -> join('products', 'order_has_products.product_id', 'products.id')
                -> join('units', 'products.unit', 'units.code')
                -> whereMatchOrder($order->id)
                -> paginate(10);

            return response()->json([
                'message' => trans('Successfully retrieved'),
                'order' => $order,
                'products_by_order' => $products_by_order,
            ], 200);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }
}
