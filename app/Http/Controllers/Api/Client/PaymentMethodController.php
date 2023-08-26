<?php

namespace App\Http\Controllers\Api\Client;

use App\Domain\Order\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentMethodController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $payment_methods = PaymentMethod::query()->get();

        return PaymentMethodResource::collection($payment_methods);
    }
}
