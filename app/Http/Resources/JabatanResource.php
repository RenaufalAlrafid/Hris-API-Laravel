<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JabatanResource extends JsonResource
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
            'divisi_id' => $this->divisi_id,
            'divisi' => $this->divisi->name,
            'atasan' => $this->atasan,
            'validator'=> $this->validator
        ];
    }
}
