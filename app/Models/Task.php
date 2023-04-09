<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function User(){
        return $this->hasMany(User::class, 'id', 'id_autor');
    }

    public function Assigned(){
        return $this->belongsTo(Assigned::class, 'id', 'id_task');
    }

    public function SubTask(){
        return $this->belongsToMany(SubTask::class);
    }

    public function Board(){
        return $this->belongsToMany(Board::class, 'task_in__boards', 'id_task', 'id_board');
    }
}
