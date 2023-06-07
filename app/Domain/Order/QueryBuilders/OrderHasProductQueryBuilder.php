<?php

namespace App\Domain\Order\QueryBuilders;

use App\Domain\Order\Models\OrderHasProduct;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static OrderHasProduct join(...$parameters)
 * @method static OrderHasProduct select(...$parameters)
 */
class OrderHasProductQueryBuilder extends Builder
{
    public function whereMatchOrder(?string $order_id): self
    {
        return $order_id ? $this->where('order_has_products.order_id', $order_id) : $this;
    }
}
