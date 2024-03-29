<?php

namespace App\Http\Requests\Web\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool|null
    {
        return auth()->user()?->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'min_stock' => ['nullable', 'integer'],
            'max_stock' => ['nullable', 'integer'],
            'min_price' => ['nullable', 'numeric'],
            'max_price' => ['nullable', 'numeric'],
            'unit_code' => ['nullable', 'string'],
            'availability' => ['nullable', 'string'],
            'sold_out' => ['nullable', 'string'],
            'time' => ['required', 'string'],
        ];
    }
}
