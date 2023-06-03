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
        $order = Order::where('id', $id)->first();
        $products_data = new StoreOrderData($get_products->handle($order->id));

        if ($order->payment_status == 'canceled') {
            $order->pending();
        }

        $placetopay->pay($order, $products_data, $request->ip(), $request->userAgent());

        return Redirect::route('order.show', $order['id']);
    }

    public function process_response(PlaceToPayPaymentServices $placetopay_payment, string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

        $order = Order::select('id')->where('code', $code)->first();

        return Redirect::route('order.show', $order['id'])->with('success', $result == 'ok' ?  'Payment completed.' : 'Payment error.');
    }

    public function process_canceled(UpdateOrderCanceledAction $cancel_order, string $code): RedirectResponse
    {
        $cancel_order->handle($code);

        return Redirect::route('showcase.index')->with('success', 'Payment canceled.');
    }

    public function process_error(): RedirectResponse
    {
        return Redirect::route('showcase.index')->with('success', 'Unexpected error.');
    }
}
