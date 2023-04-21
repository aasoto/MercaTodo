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

    public function index(): Response
    {
        return Inertia::render('Showcase/Index', [
            'products' => Product::query()
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
                -> paginate(12),
            'userRole' => $this->authHasRole($this->getRoles()),
        ]);
    }

    public function show(string $slug): Response
    {
        return Inertia::render('Product/Show', [
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
