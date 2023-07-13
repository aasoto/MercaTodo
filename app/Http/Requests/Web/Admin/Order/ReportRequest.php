<?php

namespace App\Http\Requests\Web\Admin\Order;

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
            'number_document' => ['nullable', 'string'],
            'date_1' => ['nullable', 'date'],
            'date_2' => ['nullable', 'date'],
            'payment_status' => ['nullable', 'string'],
            'min_total' => ['nullable', 'numeric'],
            'max_total' => ['nullable', 'numeric'],
            'time' => ['required', 'string'],
        ];
    }
}
