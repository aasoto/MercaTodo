<?php

namespace App\Http\Requests\Api\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
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
            'image_file' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ];
    }
}
