<?php

namespace App\Classes\Showcase;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Action
{
    public function index(Request $request): LengthAwarePaginator
    {
        return Product::query()
        -> when($request->input('search'), function ($query, $search) {
            $query->where('products.name', 'like', '%'.$search.'%');
        })
        -> when($request->input('category'), function ($query, $category) {
            $query->where('products_categories.name', $category);
        })
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
