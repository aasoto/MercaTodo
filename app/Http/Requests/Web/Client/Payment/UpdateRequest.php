<?php

namespace App\Http\Requests\Web\Client\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'id' => ['required', 'integer'],
        ];
    }
}
