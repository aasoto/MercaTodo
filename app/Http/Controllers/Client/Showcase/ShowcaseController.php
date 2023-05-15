<?php

namespace App\Http\Controllers\Client\Showcase;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Spatie\ModelHasRole as Role;
use App\Traits\AuthHasRole;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowcaseController extends Controller
{
    use AuthHasRole;

    public function index(Request $request): Response
    {
        return Inertia::render('Showcase/Index', [
            'filters' => $request->only(['search', 'category', 'minPrice', 'maxPrice']),
            'products' => Product::query()
                -> whereSearch($request->input('search'))
                -> whereCategory($request->input('category'))
                -> wherePriceBetween($request->input('minPrice'), $request->input('maxPrice'))
                -> select(
                        'products.id',
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
            'products_categories' => ProductCategory::getFromCache(),
            'userRole' =>
                session('user_role') ?
                session('user_role') :
                $this->authHasRole(Role::getFromCache()),
        ]);
    }

    public function show(string $slug): Response
    {
        return Inertia::render('Showcase/Show', [
            'product' => Product::select(
                'products.id',
                'products.name',
                'products.slug',
                'products_categories.name as category',
                'products.description',
                'products.price',
                'units.name as unit',
                'products.stock',
                'products.picture_1',
                'products.picture_2',
                'products.picture_3',
                'products.availability',
            )
            -> join('products_categories', 'products.products_category_id', 'products_categories.id')
            -> join('units', 'products.unit', 'units.code')
            -> whereSlug($slug)
            -> first(),
        ]);
    }
}
