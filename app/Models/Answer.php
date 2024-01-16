<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected $table = 'answers';
    protected $guarded = ['id'];
    public function questions(){
        return $this->belongsTo(Question::class);
    }
    public function region(){
        return $this->hasOne(Region::class);
    }
    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'stages_answers', 'answer_id', 'stage_id');
    }

    public function regions(){
        return $this->hasOneThrough(Region::class,StageAnswer::class,
            'answer_id', // Foreign key on the StageAnswer table...
            'id', // Foreign key on the Region table...
            'id', // Local key on the Answer table...
            'region_id' // Local key on the StageAnswer table...);
        );

    }

}
