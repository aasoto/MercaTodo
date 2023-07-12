<?php

namespace App\Http\Resources;

use App\Domain\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Product;
 */
class ProductResource extends JsonResource
{
    /**
     * @return array<mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'products_category_id' => $this->products_category_id,
            'product_category' => ProductsCategoryResource::make($this->whenLoaded('category')),
            'barcode' => $this->barcode,
            'description' => $this->description,
            'price' => $this->price,
            'unit' => $this->unit,
            'product_unit' => UnitResource::make($this->whenLoaded('product_unit')),
            'stock' => $this->stock,
            'picture_1' => Storage::url('images/products/'.$this->picture_1),
            'picture_2' => Storage::url('images/products/'.$this->picture_2),
            'picture_3' => Storage::url('images/products/'.$this->picture_3),
            'availability' => $this->availability,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
