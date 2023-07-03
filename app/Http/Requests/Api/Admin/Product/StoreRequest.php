<?php

namespace App\Http\Requests\Api\Admin\Product;

use App\Domain\Product\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name)
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique(Product::class)],
            'slug' => ['required', 'string', 'max:100', Rule::unique(Product::class)],
            'products_category_id' => ['required', 'integer'],
            'barcode' => ['required', 'integer', Rule::unique(Product::class)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'unit' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer'],
            'picture_1' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'picture_2' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'picture_3' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];
    }
}
