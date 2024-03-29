<?php

namespace App\Http\Requests\Api\Admin\Unit;

use App\Domain\Product\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:100', Rule::unique(Unit::class)],
            'name' => ['required', 'string', 'max:100', Rule::unique(Unit::class)],
        ];
    }
}
