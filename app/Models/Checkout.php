<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Checkout extends Model
{
    use HasFactory,softDeletes;
    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'trip_id',
        'student_id',
        'checkout',
        'note',
    ];
    public function student (){
        
        return $this->belongsTo(Student::class);
    }

    public function trip (){
        
        return $this->belongsTo(Trip::class);
    }
}
