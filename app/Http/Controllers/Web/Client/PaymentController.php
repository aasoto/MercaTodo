<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function process_response(PlaceToPayPaymentServices $placetopay_payment, string $code): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation($code);

        $order = Order::select('id')->where('code', $code)->first();

        return Redirect::route('order.show', $order['id'])->with('success', $result == 'ok' ?  'Payment completed.' : 'Payment error.');
    }
}
