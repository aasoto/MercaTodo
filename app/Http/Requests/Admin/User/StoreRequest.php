<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'type_document'     => ['required', 'string', 'max:3'],
            'number_document'   => ['required', 'regex:/^[0-9A-Z]+$/i', 'max:100', Rule::unique(User::class)->ignore($this->user()->id)],
            'first_name'        => ['required', 'string', 'max:100'],
            'second_name'       => ['nullable', 'string', 'max:100'],
            'surname'           => ['required', 'string', 'max:100'],
            'second_surname'    => ['nullable', 'string', 'max:100'],
            'email'             => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'birthdate'         => ['required', 'date', 'before:18 years'],
            'gender'            => ['required', 'regex:/^[fmo]+$/i', 'max:1'],
            'phone'             => ['required', 'regex:/^[+\\-\\(\\)\\.\\0-9x ]+$/i', 'max:100', Rule::unique(User::class)->ignore($this->user()->id)],
            'address'           => ['required', 'string', 'max:1000'],
            'state_id'          => ['required', 'integer'],
            'city_id'           => ['required', 'integer'],
            'role_id'           => ['required', 'integer']
        ];
    }
}
