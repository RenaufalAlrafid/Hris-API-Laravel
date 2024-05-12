<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CutiResource extends JsonResource
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
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'days' => $this->days,
            'description' => $this->description,
            'approve_hrd' => $this->approve_hrd,
            'approve_atasan' => $this->approve_atasan,
            'employee' => $this->employee,
        ];
    }
}
