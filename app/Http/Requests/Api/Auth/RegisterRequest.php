<?php

namespace App\Http\Requests\Api\Auth;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'type_document' => 'required|string|max:3',
            'number_document' => 'required|regex:/^[0-9A-Z]+$/i|max:100|unique:users,number_document',
            'first_name' => 'required|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'surname' => 'required|string|max:100',
            'second_surname' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birthdate' => 'required|date|before:18 years',
            'gender' => 'required|regex:/^[fmo]+$/i|max:1',
            'phone' => 'required|regex:/^[+\\-\\(\\)\\0-9x ]+$/i|max:100|unique:'.User::class,
            'address' => 'required|string|max:1000',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer'
        ];
    }
}
