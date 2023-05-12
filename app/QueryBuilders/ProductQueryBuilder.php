<?php

namespace App\QueryBuilders;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Product select(...$parameters)
 * @method static Product whereBetween(...$parameters)
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

            $enabled = '1';
            $disabled = '0';

            if ($availability == 'true') {
                $query -> where('products.availability', $enabled);
            }
            if ($availability == 'false') {
                $query -> where('products.availability', $disabled);
            }
        });
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('slug', $slug);
    }

    public function wherePriceBetween(?string $min_price, ?string $max_price): self|Product
    {
        return ($min_price && $max_price) ?
            $this->whereBetween('products.price', [$min_price, $max_price]) :
            $this;
    }
}
