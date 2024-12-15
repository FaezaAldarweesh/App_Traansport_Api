<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'driver id' => $this->id,
            'driver name' => $this->name, 
            'driver phone' => $this->phone,
            'driver location' => $this->location, 
        ];
    }
}
