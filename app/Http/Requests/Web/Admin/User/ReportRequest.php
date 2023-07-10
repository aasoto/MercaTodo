<?php

namespace App\Http\Requests\Web\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'type_document' => ['nullable', 'string'],
            'verified' => ['nullable', 'string'],
            'enabled' => ['nullable', 'string'],
            'role' => ['nullable', 'integer'],
            'date_1' => ['nullable', 'date'],
            'date_2' => ['nullable', 'date'],
            'state_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
        ];
    }
}
