<?php

namespace App\Http\Controllers\Web\Client;

use App\Domain\Order\Actions\StoreOrderAction;
use App\Domain\Order\Actions\StoreOrderHasProductAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Order\Services\PlaceToPayPaymentServices;
use App\Domain\Order\Traits\CheckStock;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Client\Order\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    use CheckStock;

    public function index(): Response
    {
        // dd(json_decode('{"requestId":73315,"status":{"status":"APPROVED","reason":"00","message":"La petición ha sido aprobada exitosamente","date":"2023-06-07T21:55:45-05:00"},"request":{"locale":"es_CO","buyer":{"name":"Bennie Karine","surname":"Crooks West","email":"client@example.com","mobile":"+1.567.891.8674"},"payer":{"document":"3498685469","documentType":"CC","name":"Bennie Karine","surname":"Crooks West","email":"client@example.com","mobile":"+15678918674"},"payment":{"reference":"6296755b81a1cca5","description":"Payment of purchase total","amount":{"currency":"COP","total":124675.28},"allowPartial":false,"items":[{"name":"Velit Vel Rerum Labore","qty":1,"price":124675.28}],"subscribe":false},"fields":[{"keyword":"_processUrl_","value":"https://checkout-co.placetopay.dev/spa/session/73315/df072adca4b3d1ded8554ba0414eb164","displayOn":"none"}],"returnUrl":"http://127.0.0.1:8000/payment/response/6296755b81a1cca5","ipAddress":"127.0.0.1","userAgent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36","expiration":"2023-06-09T02:54:44.091914Z"},"payment":[{"amount":{"to":{"total":23078.51,"currency":"CLP"},"from":{"total":124675.28,"currency":"COP"},"factor":0.185109},"status":{"date":"2023-06-07T21:55:25-05:00","reason":"00","status":"APPROVED","message":"Aprobada"},"receipt":"372459412044","refunded":false,"franchise":"PS_VS","reference":"6296755b81a1cca5","issuerName":"BANCO DE GUAYAQUIL, S.A.","authorization":"685451","paymentMethod":"visa","processorFields":[{"value":"4549106521651","keyword":"merchantCode","displayOn":"none"},{"value":"98765432","keyword":"terminalNumber","displayOn":"none"},{"value":"C","keyword":"cardType","displayOn":"none"},{"value":"411076","keyword":"bin","displayOn":"none"},{"value":1,"keyword":"installments","displayOn":"none"},{"value":"http://127.0.0.1:8000/payment/response/6296755b81a1cca5","keyword":"returnUrl","displayOn":"none"},{"value":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36","keyword":"userAgent","displayOn":"none"},{"value":"0081","keyword":"lastDigits","displayOn":"none"},{"value":"2d3d18b6ade7c2ba430e819ad22127ab","keyword":"id","displayOn":"none"},{"value":"00","keyword":"b24","displayOn":"none"}],"internalReference":425000,"paymentMethodName":"Visa"}],"subscription":null}', true));
        return Inertia::render('Order/Index', [
            'orders' => Order::query()
                -> select(
                        'id',
                        'purchase_date',
                        'payment_date',
                        'payment_status',
                        'purchase_total',
                        'url',
                    )
                -> whereAuthUser()
                -> orderByDesc('purchase_date')
                -> paginate(10),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Order/Create', [
            'limitatedStock' => session('limitatedStock'),
            'success' => session('success'),
        ]);
    }

    public function store(
        StoreRequest $request,
        StoreOrderAction $store_order_action,
        StoreOrderHasProductAction $store_order_has_product_action,
        PlaceToPayPaymentServices $placetopay): RedirectResponse
    {
        $data = StoreOrderData::fromRequest($request);

        $limitated_stock = $this->solvent_order($data);

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
            return Redirect::route('order.create')
                ->with('success', 'Order rejected.')
                ->with('limitatedStock', json_encode($limitated_stock));
        }

        return Redirect::route('order.show', $order->id)->with('success', 'Order created.');
    }

    public function show(string $id): Response
    {
        return Inertia::render('Order/Show', [
            'order' => Order::query()
                -> select(
                        'id',
                        'purchase_date',
                        'payment_status',
                        'purchase_total',
                        'url',
                    )
                -> where('id', $id)
                -> first(),
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
                -> paginate(10),
            'success' => session('success'),
        ]);
    }
}
