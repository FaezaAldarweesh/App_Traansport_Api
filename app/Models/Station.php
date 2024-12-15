<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Station extends Model
{
    use HasFactory,softDeletes;
    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'path_id',
    ];

    public function path (){
        
        return $this->belongsTo(Path::class,'path_id','id');

    }
}
