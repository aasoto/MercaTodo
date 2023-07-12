<?php

namespace App\Http\Exports;

use App\Domain\Product\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class ProductsReport implements FromQuery
{
    use Exportable;

    /**
     * @param array<mixed> $filters
     */
    public function __construct(
        private array $filters,
    )
    {}

    public function query()
    {
        /**
         * @var Builder|EloquentBuilder|Relation $query;
         */
        $query = Product::query()
            -> whereSearch(isset($this->filters['search']) ? $this->filters['search'] : null)
            -> whereCategory(isset($this->filters['category']) ? $this->filters['category'] : null)
            -> whereUnit(isset($this->filters['unit_code']) ? $this->filters['unit_code'] : null)
            -> whenAvailability(isset($this->filters['availability']) ? $this->filters['availability'] : null)
            -> whereSoldOut(isset($this->filters['sold_out']) ? $this->filters['sold_out'] : null)
            -> whereStockBetween(
                isset($this->filters['min_stock']) ? $this->filters['min_stock'] : null,
                isset($this->filters['max_stock']) ? $this->filters['max_stock'] : null,
            )
            -> wherePriceBetween(
                isset($this->filters['min_price']) ? $this->filters['min_price'] : null,
                isset($this->filters['max_price']) ? $this->filters['max_price'] : null,
            )
            -> select(
                'products.id',
                'products.name',
                'products_categories.name as category',
                'products.price',
                'units.name as unit',
                'products.stock',
                'products.availability',
            )
            -> join('products_categories', 'products.products_category_id', 'products_categories.id')
            -> join('units', 'products.unit', 'units.code')
            -> orderBy('products.id');

        return $query;
    }
}
