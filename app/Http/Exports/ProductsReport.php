<?php

namespace App\Http\Exports;

use App\Domain\Product\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class ProductsReport implements FromQuery
{
    use Exportable;

    public function __construct(
        private array $filters,
    )
    {}

    public function query()
    {
        return Product::query()
            -> whereCategory(isset($this->filters['category']) ? $this->filters['category'] : null)
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
    }
}
