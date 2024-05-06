<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nip' => $this->nip,
            'name' => $this->name,
            'status' => $this->status,
            'tanggal_masuk' => $this->tanggal_masuk,
            'kelamin' => $this->kelamin,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'nik' => $this->nik,
            'npwp' => $this->npwp,
            'provinsi' => $this->provinsi,
            'kabupaten' => $this->kabupaten,
            'kecamatan' => $this->kecamatan,
            'kelurahan' => $this->kelurahan,
            'alamat_jalan' => $this->alamat_jalan,
            'kode_pos' => $this->kode_pos,
            'jenjang_pendidikan' => $this->jenjang_pendidikan,
            'pendidikan' => $this->pendidikan->nama,
            'status_pernikahan' => $this->status_pernikahan,
            'nama_pasangan' => $this->nama_pasangan,
            'nama_wali' => $this->nama_wali,
            'nomor_darurat' => $this->nomor_darurat,
            'agama' => $this->agama,
            'bpjs_tk' => $this->bpjs_tk,
            'bpjs_kes' => $this->bpjs_kes,
        ];
    }
}
