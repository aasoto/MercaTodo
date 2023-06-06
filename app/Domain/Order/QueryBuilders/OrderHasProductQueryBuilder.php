<?php

namespace App\Domain\Order\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class OrderHasProductQueryBuilder extends Builder
{
    public function whereMatchOrder(?string $order_id): self
    {
        return $order_id ? $this->where('order_has_products.order_id', $order_id) : $this;
    }
}
