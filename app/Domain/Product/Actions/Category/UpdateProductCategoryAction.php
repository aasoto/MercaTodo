<?php

namespace App\Domain\Product\Actions\Category;

use App\Domain\Product\Dtos\Category\UpdateProductCategoryData;
use App\Domain\Product\Models\ProductCategory;

class UpdateProductCategoryAction
{
    public function handle(string $id, UpdateProductCategoryData $data): void
    {
        ProductCategory::where('id', $id)->update([
            'name' => $data->name,
        ]);
    }
}
