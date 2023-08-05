<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Actions\Category\StoreProductCategoryAction;
use App\Domain\Product\Actions\Category\UpdateProductCategoryAction;
use App\Domain\Product\Dtos\Category\StoreProductCategoryData;
use App\Domain\Product\Dtos\Category\UpdateProductCategoryData;
use App\Domain\Product\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\ProductCategory\StoreRequest;
use App\Http\Requests\Web\Admin\ProductCategory\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Product/Category/Index', [
            'categories' => ProductCategory::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Product/Category/Create');
    }

    public function store(
        StoreRequest $request,
        StoreProductCategoryAction $store_product_category_action): RedirectResponse
    {
        $data = StoreProductCategoryData::fromRequest($request);
        $store_product_category_action->handle($data);
        return Redirect::route('product_category.index')->with('success', 'Product category created.');
    }

    public function edit(string $id): Response
    {
        return Inertia::render('Product/Category/Edit', [
            'category' => ProductCategory::select(
                    'id',
                    'name',
                )
                ->where('id', $id)->first(),
        ]);
    }

    public function update(
        UpdateRequest $request,
        UpdateProductCategoryAction $update_product_category_action,
        string $id): RedirectResponse
    {
        $data = UpdateProductCategoryData::fromRequest($request);
        $response = $update_product_category_action->handle($id, $data);
        return Redirect::route('product_category.index')->with('success', 'Product category updated.');
    }
}
