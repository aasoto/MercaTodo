<?php

namespace App\Domain\Product\Actions\Category;

use App\Domain\Product\Dtos\Category\StoreProductCategoryData;
use App\Domain\Product\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;

class StoreProductCategoryAction
{
    public function handle(StoreProductCategoryData $data): void
    {
        ProductCategory::create([
            'name' => $data->name,
        ]);

        Cache::put('products_categories',
            ProductCategory::select('id', 'name')->orderBy('name')->get()
        );
    }
}
