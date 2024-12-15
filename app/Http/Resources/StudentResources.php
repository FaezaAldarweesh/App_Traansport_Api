<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student id' => $this->id,
            'student name' => $this->name, 
            'student father phone' => $this->father_phone,
            'student mather phone' => $this->mather_phone,
            'student longitude' => $this->longitude,
            'student latitude' => $this->latitude,
            'student parent' => $this->user->name,
            'student status' => $this->status ?? 'attendee',
        ];
    }
}
