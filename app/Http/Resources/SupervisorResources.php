<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'supervisor id' => $this->id,
            'supervisor name' => $this->name, 
            'supervisor username' => $this->username,
            'supervisor location' => $this->location,
            'supervisor phone' => $this->phone,
        ];
    }
}
