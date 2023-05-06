<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ShowProductAction
{
    public function handle(string $slug): Model|null
    {
        return Product::select(
            'products.name',
            'products.slug',
            'products_categories.name as category',
            'products.description',
            'products.price',
            'units.name as unit',
            'products.stock',
            'products.picture_1',
            'products.picture_2',
            'products.picture_3'
        )
        -> join('products_categories', 'products.products_category_id', 'products_categories.id')
        -> join('units', 'products.unit', 'units.code')
        -> where('slug', $slug)
        -> first();
    }
}
