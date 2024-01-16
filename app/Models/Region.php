<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    protected $table = 'regions';
    protected $guarded = ['id'];
    public function answer(){
        return $this->belongsTo(Answer::class);
    }
    public function points(){
        return $this->hasMany(Point::class);
    }
    public function stageanswer(){
        return $this->hasOne(StageAnswer::class);
    }
}
