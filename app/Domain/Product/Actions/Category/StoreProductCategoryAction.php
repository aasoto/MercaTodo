<?php

namespace App\Domain\Product\Actions\Category;

use App\Domain\Product\Dtos\Category\StoreProductCategoryData;
use App\Domain\Product\Models\ProductCategory;

class StoreProductCategoryAction
{
    public function handle(StoreProductCategoryData $data): void
    {
        ProductCategory::create([
            'name' => $data->name,
        ]);
    }
}
