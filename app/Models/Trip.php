<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory,softDeletes;
    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'type',
        'path_id',
        'bus_id',
        'status',
    ];

    public function path (){
        
        return $this->belongsTo(Path::class);
    }
    public function students (){
        
        return $this->belongsToMany(Student::class);
    }

    public function supervisors (){
        
        return $this->belongsToMany(Supervisor::class);
    }

    public function drivers (){
        
        return $this->belongsToMany(Driver::class);
    }

    public function bus (){
        
        return $this->belongsTo(Bus::class);
    }
}
