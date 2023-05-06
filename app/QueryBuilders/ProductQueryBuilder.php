<?php

namespace App\QueryBuilders;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Product select(...$parameters)
 */
class ProductQueryBuilder extends Builder
{
    public function whereSearch(?string $search): self
    {
        return $search ? $this -> where('products.name', 'like', '%'.$search.'%') : $this;
    }

    public function whereCategory(?string $search): self
    {
        return $search ? $this-> where('products_categories.name', $search) : $this;
    }

    public function whenAvailability(?string $search): self
    {
        if ($search === null) {
            return $this;
        }

        return $this->when($search, function ($query, $availability) {
            if ($availability == 'true') {
                $query -> where('products.availability', '1');
            }
            if ($availability == 'false') {
                $query -> where('products.availability', '0');
            }
        });
    }
}
