<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function process_response(PlaceToPayPaymentServices $placetopay_payment): Response
    {
        $result = $placetopay_payment->getRequestInformation();

        return Inertia::render('Order/Index', [
            'sucess' => $result == 'ok' ?  'Payment completed.' : 'Payment error.',
        ]);
    }
}
