<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $table = 'permissions';
    protected $guarded = ['id'];
    public function roles(){
        return $this->belongsTo(Role::class);
    }

}
