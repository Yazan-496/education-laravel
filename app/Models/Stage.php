<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{

    protected $table = 'stages';
    protected $guarded = ['id'];
    public function questions(){
    return $this->belongsTo(Question::class);
}
    public function answers()
    {
        return $this->belongsToMany(Answer::class, 'stages_answers', 'stage_id', 'answer_id');
    }
}
