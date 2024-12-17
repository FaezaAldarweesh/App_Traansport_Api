<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user id' => $this->id,
            'user name' => $this->name, 
            'user email' => $this->email, 
            'user role' => $this->role ?? 'user', 
            'user students' => $this->students->map(function($student) {
                return [
                    'student id' => $student->id,
                    'student name' => $student->name,
                    'student father_phone' => $student->father_phone,
                    'student mather_phone' => $student->mather_phone,
                    'student longitude' => $student->longitude,
                    'student latitude' => $student->latitude,
                ];
            }),
        ];
    }
}
