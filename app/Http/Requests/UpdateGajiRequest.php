<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGajiRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'gaji_pokok' => ['nullable', 'numeric'],
            'tambahan' => ['nullable', 'numeric'],
            'potongan' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
        ];
    }
}
