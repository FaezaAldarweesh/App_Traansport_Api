<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bus extends Model
{
    use HasFactory,softDeletes;

    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'number_of_seats',
    ];
    public function trips (){
        
        return $this->hasMany(Trip::class);
    }
}
