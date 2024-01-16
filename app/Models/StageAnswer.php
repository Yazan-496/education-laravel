<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageAnswer extends Model
{
    protected $table = 'stages_answers';
    protected $guarded = ['id'];
    public function region(){
        return $this->hasOne(Region::class);
    }
}
