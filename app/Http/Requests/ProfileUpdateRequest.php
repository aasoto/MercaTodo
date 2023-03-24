<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'        => ['required', 'string', 'max:100'],
            'second_name'       => ['nullable', 'string', 'max:100'],
            'surname'           => ['required', 'string', 'max:100'],
            'second_surname'    => ['nullable', 'string', 'max:100'],
            'email'             => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'birthdate'         => ['required', 'date', 'before:18 years'],
            'gender'            => ['required', 'regex:/^[fmo]+$/i'],
            'phone'             => ['required', 'regex:/^[+\\-\\0-9]+$/i', 'max:100'],
            'address'           => ['required', 'string', 'max:1000'],
            'state_id'          => ['required', 'integer'],
            'city_id'           => ['required', 'integer'],
        ];
    }
}
