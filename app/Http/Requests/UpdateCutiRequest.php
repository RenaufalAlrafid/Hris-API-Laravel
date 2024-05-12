<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCutiRequest extends FormRequest
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
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date'],
            'days' => ['nullable', 'numeric'],
            'description' => ['required', 'string'],
            'approve_hrd' => ['nullable', 'boolean'],
            'approve_atasan' => ['nullable', 'boolean'],
            'employee_id' => ['required', 'exists:employees,id'],
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date_start.date' => 'Tanggal mulai harus berupa tanggal',
            'date_end.date' => 'Tanggal selesai harus berupa tanggal',
            'days.numeric' => 'Jumlah hari harus berupa angka',
            'description.required' => 'Deskripsi harus diisi',
            'description.string' => 'Deskripsi harus berupa string',
            'approve_hrd.boolean' => 'Approve HRD harus berupa boolean',
            'approve_atasan.boolean' => 'Approve Atasan harus berupa boolean',
            'employee_id.required' => 'ID Karyawan harus diisi',
            'employee_id.exists' => 'ID Karyawan tidak ditemukan',
        ];
    }



    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
    
}
