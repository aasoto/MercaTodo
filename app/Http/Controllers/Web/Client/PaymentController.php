<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function process_response(PlaceToPayPaymentServices $placetopay_payment): RedirectResponse
    {
        $result = $placetopay_payment->getRequestInformation();

        return Redirect::route('order.index')->with('success', $result == 'ok' ?  'Payment completed.' : 'Payment error.');
    }
}
