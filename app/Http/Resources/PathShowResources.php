<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PathShowResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'path id' => $this->id,
            'path name' => $this->name, 
            'path station' => $this->stations->map(function($station) {
                return [
                    'user id' => $station->id,
                    'user name' => $station->name,
                    'user status' => $station->status,
                ];
            }),
        ];
    }
}
