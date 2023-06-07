<?php

namespace App\Http\Requests\Web\Client\Order;

use App\Domain\Product\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool|null
    {
        return auth()->user()?->hasRole('client');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'products'                  => ['required', 'array'],
            'products.*.id'             => ['required', 'integer', Rule::exists(Product::class)],
            'products.*.name'           => ['nullable', 'string', 'max:100'],
            'products.*.slug'           => ['nullable', 'string', 'max:100'],
            'products.*.price'          => ['required', 'numeric'],
            'products.*.quantity'       => ['required', 'integer'],
            'products.*.totalPrice'     => ['required', 'numeric'],
        ];
    }
}
