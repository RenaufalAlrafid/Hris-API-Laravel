<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeUpdateRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'nip' => 'nullable|string',
            'name' => 'nullable|string',
            'status' => 'nullable|string',
            'tanggal_masuk' => 'nullable|date',
            'kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nik' => 'nullable|string',
            'npwp' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kelurahan' => 'nullable|string',
            'alamat_jalan' => 'nullable|string',
            'kode_pos' => 'nullable|string',
            'jenjang_pendidikan' => 'nullable|string',
            'pendidikan' => 'nullable|string',
            'status_pernikahan' => 'nullable|string',
            'nama_pasangan' => 'nullable|string',
            'nama_wali' => 'nullable|string',
            'nomor_darurat' => 'nullable|string',
            'agama' => 'nullable|string',
            'bpjs_tk' => 'nullable|string',
            'bpjs_kes' => 'nullable|string',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        // in indonesia
        return [
            'user_id.exists' => 'User ID tidak ditemukan',
            'tanggal_masuk.date' => 'Tanggal masuk harus berupa tanggal',
            'kelamin.string' => 'Kelamin harus berupa string',
            'tempat_lahir.string' => 'Tempat lahir harus berupa string',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal',
            'nik.string' => 'NIK harus berupa string',
            'npwp.string' => 'NPWP harus berupa string',
            'provinsi.string' => 'Provinsi harus berupa string',
            'kabupaten.string' => 'Kabupaten harus berupa string',
            'kecamatan.string' => 'Kecamatan harus berupa string',
            'kelurahan.string' => 'Kelurahan harus berupa string',
            'alamat_jalan.string' => 'Alamat jalan harus berupa string',
            'kode_pos.string' => 'Kode pos harus berupa string',
            'jenjang_pendidikan.string' => 'Jenjang pendidikan harus berupa string',
            'pendidikan.string' => 'Pendidikan harus berupa string',
            'status_pernikahan.string' => 'Status pernikahan harus berupa string',
            'nama_pasangan.string' => 'Nama pasangan harus berupa string',
            'nama_wali.string' => 'Nama wali harus berupa string',
            'nomor_darurat.string' => 'Nomor darurat harus berupa string',
            'agama.string' => 'Agama harus berupa string',
            'bpjs_tk.string' => 'BPJS TK harus berupa string',
            'bpjs_kes.string' => 'BPJS KES harus berupa string',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
