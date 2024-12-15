<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory,softDeletes;
    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'phone',
        'location',
    ];
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = $value['first_name'] . ' ' . $value['last_name'];
    // }
    public function trips (){
        
        return $this->belongsToMany(Trip::class);
    }
}
