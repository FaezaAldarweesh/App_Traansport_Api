<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends Model
{
    use HasFactory,SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'location',
        'phone',
    ];    
    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed'
    ];

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = $value['first_name'] . ' ' . $value['last_name'];
    // }

    public function trips (){
        
        return $this->belongsToMany(Trip::class);
    }
}