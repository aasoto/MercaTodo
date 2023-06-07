<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Actions\GetProductsByOrderAction;
use App\Domain\Order\Actions\UpdateOrderCanceledAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use App\Http\Requests\Web\Client\Payment\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function update(
        GetProductsByOrderAction $get_products,
        PlaceToPayPaymentServices $placetopay,
        UpdateRequest $request,
        string $id)
    {
        /**
         * @var Order $order
         */
        $order = Order::where('id', $id)->first();
        $products_data = new StoreOrderData($get_products->handle($order->id));

        if ($order->payment_status == 'canceled') {
            $order->pending();
        }

        $status = $placetopay->pay($order, $products_data, $request->ip(), $request->userAgent());

        if ($status === 200) {
            return Redirect::route('order.show', $order['id']);
        } else {
            return Redirect::route('payment.error', $status);
        }
    }

    public function process_response(PlaceToPayPaymentServices $placetopay_payment, string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

        $order = Order::select('id')->where('code', $code)->first();

        return Redirect::route('order.show', $order['id'])->with('success', $result == 'ok' ?  'Payment completed.' : 'Payment error.');
    }

    public function process_canceled(PlaceToPayPaymentServices $placetopay_payment, string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

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
