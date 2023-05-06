<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexProductAction
{
    public function handle(Request $request): LengthAwarePaginator
    {
        return Product::query()
            -> whereSearch($request->input('search'))
            -> whereCategory($request->input('category'))
            -> whenAvailability($request->input('availability'))
            -> select(
                    'products.name',
                    'products.slug',
                    'products_categories.name as category',
                    'products.price',
                    'units.name as unit',
                    'products.stock',
                    'products.availability',
                )
            -> join('products_categories', 'products.products_category_id', 'products_categories.id')
            -> join('units', 'products.unit', 'units.code')
            -> orderBy('products.id')
            -> paginate(10)
            -> withQueryString();
    }
}
