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
            -> when($request->input('search'), function ($query, $search) {
                $query -> where('products.name', 'like', '%'.$search.'%');
            })
            -> when($request->input('category'), function ($query, $category) {
                $query -> where('products_categories.name', $category);
            })
            -> when($request->input('availability'), function ($query, $availability) {
                if ($availability == 'true') {
                    $query -> where('products.availability', '1');
                }
                if ($availability == 'false') {
                    $query -> where('products.availability', '0');
                }
            })
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
