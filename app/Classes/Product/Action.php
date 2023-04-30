<?php

namespace App\Classes\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Action
{
    public function index(Request $request): LengthAwarePaginator
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

    /**
     * @param array<mixed> $data
     */
    public function create(array $data): void
    {
        Product::create($data);
    }

    public function show(string $slug): Product|null
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

    public function edit(string $slug): Product|null
    {
        return Product::select(
            'products.id',
            'products.name',
            'products.products_category_id',
            'products.barcode',
            'products.description',
            'products.price',
            'products.unit',
            'products.stock',
            'products.picture_1',
            'products.picture_2',
            'products.picture_3',
            'products.availability'
        )
        -> where('slug', $slug)
        -> first();
    }

    /**
     * @param array<mixed> $data
     */
    public function update(string $id, array $data): void
    {
        Product::where('id', $id)->update($data);
    }

    public function delete(string $slug): void
    {
        Product::where('slug', $slug)->delete();
    }
}
