<?php

namespace App\Domain\Product\QueryBuilders;

use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

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
        if ($min_price && $max_price) {
            return $this->whereBetween('products.price', [$min_price, $max_price]);
        } elseif ($min_price) {
            return $this->where('products.price', $min_price);
        } elseif ($max_price) {
            return $this->where('products.price', $max_price);
        } else {
            return $this;
        }
    }

    public function whereStockBetween(?int $min_stock, ?int $max_stock): self|Product
    {
        if ($min_stock && $max_stock) {
            return $this->whereBetween('products.stock', [$min_stock, $max_stock]);
        } elseif ($min_stock) {
            return $this->where('products.stock', $min_stock);
        } elseif ($max_stock) {
            return $this->where('products.stock', $max_stock);
        } else {
            return $this;
        }
    }

    public function whereUnit(?string $unit_code): self
    {
        return $unit_code ? $this->where('products.unit', $unit_code) : $this;
    }

    public function whereSoldOut(?string $sold_out): self
    {
        if ($sold_out == 'true') {
            return $this->where('products.stock', 0);
        } else {
            return $this;
        }
    }

    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(Product::class)
        ->allowedFilters(['name', 'price', 'availability', 'products_category_id', 'unit'])
        ->allowedIncludes(['category', 'product_unit']);
    }

    public function queryBuilderShow(): QueryBuilder
    {
        return QueryBuilder::for(Product::class)
        ->allowedIncludes(['category', 'product_unit']);
    }
}
