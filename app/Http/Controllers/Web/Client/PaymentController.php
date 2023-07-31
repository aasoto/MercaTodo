<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Actions\GetProductsByOrderAction;
use App\Domain\Order\Actions\RemoveProductOfOrder;
use App\Domain\Order\Actions\UpdateOrderAction;
use App\Domain\Order\Actions\UpdateOrderHasProductAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Dtos\UpdateOrderData;
use App\Domain\Order\Dtos\UpdateOrderHasProductData;
use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use App\Domain\Order\Services\RestoreStockProductsService;
use App\Domain\Order\Traits\Cart;
use App\Domain\Order\Traits\CheckStock;
use App\Domain\Product\Actions\UpdateProductAction;
use App\Domain\Product\Dtos\UpdateProductData;
use App\Domain\Product\Models\Product;
use App\Http\Requests\Web\Client\Payment\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PaymentController extends Controller
{
    use CheckStock;

    public function show(string $code): HttpFoundationResponse
    {
        /**
         * @var Order $order
         */
        $order = Order::select('url')->where('code', $code)->first();

        return Inertia::location($order['url']);
    }

    public function update(
        PlaceToPayPaymentServices $placetopay,
        UpdateRequest $request,
        string $id): RedirectResponse
    {
        /**
         * @var Order $order
         */
        $order = Order::where('id', $id)->first();

        $restore_stock_products = new RestoreStockProductsService($order, $request);

        $products_data = $restore_stock_products->getProducts();

        if ($order->payment_status == 'canceled') {

            $limitated_stock = $restore_stock_products->insolventProducts($products_data);

            if (count($limitated_stock) == 0) {

                $restore_stock_products->updateOrder();

                $restore_stock_products->updateOrdersProductsDecrementStock();

                $order->pending();
            } else {
                return Redirect::route('order.create')
                    ->with('success', 'Order rejected.')
                    ->with('cancel', 'Restore order.')
                    ->with('limitatedStock', json_encode($limitated_stock))
                    ->with('cartData', json_encode($products_data))
                    ->with('orderId', $order->id);
            }
        }

        $status = $placetopay->pay(
            $order,
            $products_data,
            $request->ip() ? $request->ip() : '',
            $request->userAgent() ? $request->userAgent() : ''
        );

        if ($status === 200) {
            return Redirect::route('order.show', $order['id'])
            ->with('success',
                isset($request->validated()['products']) ?
                'Payment link updated.' : ''
            );
        } else {
            return Redirect::route('payment.error', $status);
        }
    }

    public function process_response(PlaceToPayPaymentServices $placetopay_payment, string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

        /**
         * @var Order $order
         */
        $order = Order::select('id')->where('code', $code)->first();

        return Redirect::route('order.show', $order['id'])->with('success', $result == 'ok' ?  'Payment completed.' : 'Payment error.');
    }

    public function process_canceled(
        PlaceToPayPaymentServices $placetopay_payment,
        string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

        /**
         * @var Order $order
         */
        $order = Order::where('code', $code)->first();

        (new RestoreStockProductsService($order, null))
            ->updateOrdersProductsIncrementStock();

        return Redirect::route('showcase.index')->with('success', $result == 'ok' ?  'Payment canceled.' : 'Error.');
    }

    public function process_error(int $status): RedirectResponse
    {
        switch ($status) {
            case 401:
                $message = 'Payment unauthorized.';
                break;
            case 500:
                $message = 'Payment error.';
                break;
            default:
                $message = 'Payment undefined error.';
                break;
        }

        return Redirect::route('showcase.index')->with('success', $message);
    }
}
