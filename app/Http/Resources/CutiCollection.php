<?php

namespace App\Http\Resources;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CutiCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CutiResource::collection($this->collection),
        ];
    }
}
