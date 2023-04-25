<?php

namespace App\Http\Controllers\Client\Showcase;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\AuthHasRole;
use App\Traits\useCache;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowcaseController extends Controller
{
    use AuthHasRole, useCache;

    public function index(Request $request): Response
    {
        return Inertia::render('Showcase/Index', [
            'filters' => $request->only(['search', 'category']),
            'products' => Product::query()
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
                -> withQueryString(),
            'products_categories' => $this->getProductsCategories(),
            'userRole' => session('user_role') ? session('user_role') : $this->authHasRole($this->getRoles()),
        ]);
    }

    public function show(string $slug): Response
    {
        return Inertia::render('Showcase/Show', [
            'product' => Product::select(
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
            -> first(),
        ]);
    }
}
