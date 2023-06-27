<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Traits\AuthHasRole;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole;

    public function index(): Response
    {
        $products_data = array();
        $categories_data = array();
        $colors = array();

        foreach (ProductCategory::getFromCache() as $key => $value) {
            array_push($products_data, count(Product::where('products_category_id', $value['id'])->get()));
            array_push($categories_data, ucwords($value['name']));
            array_push($colors, 'rgb('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).')');
        }

        return Inertia::render('Dashboard', [
            'categoriesData' => $categories_data,
            'colors' => $colors,
            'productsData' => $products_data,
            'userRole' =>
                session('user_role') ? session('user_role') : $this->authHasRole(Role::getFromCache()),
        ]);
    }

}
