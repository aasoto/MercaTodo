<?php

namespace App\Domain\Product\Actions\Category;

use App\Domain\Product\Dtos\Category\UpdateProductCategoryData;
use App\Domain\Product\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;

class UpdateProductCategoryAction
{
    public function handle(string $id, UpdateProductCategoryData $data): void
    {
        ProductCategory::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('products_categories',
            ProductCategory::select('id', 'name')->orderBy('name')->get()
        );
    }
}
