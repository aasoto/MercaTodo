<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Actions\GetProductsByOrderAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
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
    public function show(string $code): HttpFoundationResponse
    {
        /**
         * @var Order $order
         */
        $order = Order::select('url')->where('code', $code)->first();

        return Inertia::location($order['url']);
    }

    public function update(
        GetProductsByOrderAction $get_products,
        PlaceToPayPaymentServices $placetopay,
        UpdateRequest $request,
        string $id): RedirectResponse
    {
        /**
         * @var Order $order
         */
        $order = Order::where('id', $id)->first();
        $products_data = new StoreOrderData($get_products->handle($order->id));

        if ($order->payment_status == 'canceled') {
            $order->pending();
        }

        $status = $placetopay->pay(
            $order,
            $products_data,
            $request->ip() ? $request->ip() : '',
            $request->userAgent() ? $request->userAgent() : ''
        );

        if ($status === 200) {
            return Redirect::route('order.show', $order['id']);
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
        UpdateProductAction $update_product_action,
        string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

        /**
         * @var Order $order
         */
        $order = Order::where('code', $code)->first();
        foreach ($order->products as $key => $value) {
            /**
             * @var Product $product
             */
            $product = $value->product;
            $update_product_data = new UpdateProductData(
                $product->name,
                $product->slug,
                strval($product->products_category_id),
                $product->barcode,
                $product->description,
                strval($product->price),
                $product->unit,
                strval($product->stock + $value->quantity),
                null,
                null,
                null,
                $product->availability,
            );

            $update_product_action->handle($update_product_data, strval($product->id), '[]');
        }

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
