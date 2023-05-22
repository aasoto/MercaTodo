<?php

namespace App\Http\Requests\Web\Admin\TypeDocument;

use App\Domain\User\Models\TypeDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:3', Rule::unique(TypeDocument::class)],
            'name' => ['required', 'string', 'max:50', Rule::unique(TypeDocument::class)],
        ];
    }
}
