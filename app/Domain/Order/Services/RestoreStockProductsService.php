<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Actions\GetProductsByOrderAction;
use App\Domain\Order\Actions\RemoveProductOfOrder;
use App\Domain\Order\Actions\UpdateOrderAction;
use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Dtos\UpdateOrderData;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Traits\Cart;
use App\Domain\Order\Traits\CheckStock;
use App\Domain\Product\Actions\UpdateProductAction;
use App\Domain\Product\Dtos\UpdateProductData;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Services\ImagesServices;
use App\Http\Requests\Web\Client\Payment\UpdateRequest;

class RestoreStockProductsService
{
    use Cart, CheckStock;

    public function __construct(
        public Order $order,
        public ?UpdateRequest $request,
    )
    {}

    public function get_products(): StoreOrderData
    {
        $get_products = new GetProductsByOrderAction();

        if (isset($this->request->validated()['products'])) {
            $products_data = new StoreOrderData($get_products->handle($this->order->id, true, $this->request->validated()['products']), $this->request->validated()['payment_method']);
        } else {
            $products_data = new StoreOrderData($get_products->handle($this->order->id, false, null), $this->request->validated()['payment_method']);
        }

        return $products_data;
    }

    /**
     * @return array<mixed>
     */
    public function insolvent_products(StoreOrderData $products_data): array
    {
        if (isset($this->request->validated()['products'])) {
            $limitated_stock = $this->solvent_order((new StoreOrderData($this->get_cart(true, $products_data->products), $this->request->validated()['payment_method'])));
        }else {
            $limitated_stock = $this->solvent_order((new StoreOrderData($this->get_cart(false, $this->order), $this->request->validated()['payment_method'])));
        }

        return $limitated_stock;
    }

    public function update_order(): void
    {
        $update_order_action = new UpdateOrderAction();

        if (isset($this->request->validated()['products'])) {
            $purchase_total = 0;
            foreach ($this->request->validated()['products'] as $key => $value) {
                $purchase_total = $purchase_total + $value['totalPrice'];
            }
            $order_data = new UpdateOrderData(null, strval($purchase_total));
            $update_order_action->handle($order_data, $this->order['code']);
        }
    }

    public function update_orders_products_decrement_stock(): void
    {
        $remove_product_of_order = new RemoveProductOfOrder();
        $update_product_action = new UpdateProductAction((new ImagesServices));

        foreach ($this->order->products as $key => $value) {
            /**
             * @var Product $product
             */
            $product = $value->product;
            if ($product->stock != 0) {
                $this->update_products($product, $value->quantity, $update_product_action, false);
            } else {
                $remove_product_of_order->handle($this->order->id, strval($product->id));
            }

        }
    }

    public function update_orders_products_increment_stock(): void
    {
        $update_product_action = new UpdateProductAction((new ImagesServices));

        foreach ($this->order->products as $key => $value) {
            /**
             * @var Product $product
             */
            $product = $value->product;
            $this->update_products($product, $value->quantity, $update_product_action);
        }
    }

    private function update_products(
        Product $product,
        int $quantity,
        UpdateProductAction $update_product_action,
        bool $increment = true): void
    {
        $update_product_data = new UpdateProductData(
            $product->name,
            $product->slug,
            strval($product->products_category_id),
            $product->barcode,
            $product->description,
            strval($product->price),
            $product->unit,
            strval($increment ?
                $this->increment_stock($product->stock, $quantity) :
                $this->decrement_stock($product->stock, $quantity)
            ),
            null,
            null,
            null,
            boolval($product->availability),
        );

        $update_product_action->handle($update_product_data, strval($product->id), '[]');
    }

    private function increment_stock(int $stock, int $quantity): int
    {
        return  $stock + $quantity;
    }

    private function decrement_stock(int $stock, int $quantity): int
    {
        return  $stock - $quantity;
    }
}
