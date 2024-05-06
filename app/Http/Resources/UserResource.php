<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'jabatan' => $this->jabatan->name ?? null,
            'jabatan_id' => $this->jabatan_id ?? null,
            'employee' => $this->employee ? new EmployeeResource($this->employee) : null,
        ];
    }
}
