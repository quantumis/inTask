<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    public function Task(){
        return $this->belongsToMany(Task::class, 'task_in__boards', 'id_board', 'id_task');
    }

    public function Access(){
        return $this->belongsTo(Access::class, 'id', 'id_board');
    }

    public function User(){
        return $this->hasMany(User::class, 'id_creator', 'id');
    }
}
