<?php

namespace App\Http\Resources;

use App\Domain\Product\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductCategory;
 */
class ProductsCategoryResource extends JsonResource
{
    /**
     * @return array<mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
