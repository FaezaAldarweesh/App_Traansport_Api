<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'checkout id' => $this->id,
            'checkout trip' => $this->trip->name, 
            'checkout trip type' => $this->trip->type, 
            'checkout student' => $this->student->name,
            'checkout checkout' => $this->checkout == 0 ? 'غياب' : 'حضور', 
            'checkout note' => $this->note,
        ];
    }
}
