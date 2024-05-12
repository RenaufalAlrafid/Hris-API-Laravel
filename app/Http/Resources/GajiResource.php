<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GajiResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'gaji_pokok' => $this->gaji_pokok,
            'tunjangan' => $this->tunjangan,
            'potongan' => $this->potongan,
            'total_gaji' => $this->total_gaji,
            'employee' => $this->employee,
        ];
    }
}
