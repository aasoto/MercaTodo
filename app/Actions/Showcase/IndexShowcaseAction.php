<?php

namespace App\Actions\Showcase;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexShowcaseAction
{
    public function handle(Request $request): LengthAwarePaginator
    {
        return Product::query()
        -> whereSearch($request->input('search'))
        -> whereCategory($request->input('category'))
        -> wherePriceBetween($request->input('minPrice'), $request->input('maxPrice'))
        -> select(
                'products.name',
                'products.slug',
                'products_categories.name as category',
                'products.price',
                'units.name as unit',
                'products.picture_1'
            )
        -> join('products_categories', 'products.products_category_id', 'products_categories.id')
        -> join('units', 'products.unit', 'units.code')
        -> orderBy('products.id')
        -> where('availability', 1)
        -> paginate(12)
        -> withQueryString();
    }
}
