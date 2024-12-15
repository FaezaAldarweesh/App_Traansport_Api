<?php 

namespace App\Http\Traits;

use App\Models\Trip;
use App\Models\Student;

trait AllStudentsByTripTrait 
{
    public function All_Students_By_Trip($trip_id){
        
        $trip = Trip::findOrFail($trip_id);  
        $studentsIds = $trip->students->pluck('student_id');
        $students = Student::whereIn('id', $studentsIds)->get();

        return $students;
    }

}