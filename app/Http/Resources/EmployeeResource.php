<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'email' => $this->user->email,
            'nip' => $this->nip,
            'name' => $this->name,
            'status' => $this->status,
            'tanggal_masuk' => $this->tanggal_masuk,
            'jabatan' => JabatanResource::make($this->jabatan),
        ];
    }
}
