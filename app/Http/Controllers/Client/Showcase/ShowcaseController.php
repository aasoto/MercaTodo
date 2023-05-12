<?php

namespace App\Http\Controllers\Client\Showcase;

use App\Actions\Showcase\IndexShowcaseAction;
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

    public function index(Request $request, IndexShowcaseAction $index_showcase_action): Response
    {
        return Inertia::render('Showcase/Index', [
            'filters' => $request->only(['search', 'category', 'minPrice', 'maxPrice']),
            'products' => $index_showcase_action->handle($request),
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
