<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_in_Task extends Model
{
    use HasFactory;

    public function SubTask(){
        return $this->hasMany(SubTask::class, 'id', 'id_subtask');
    }

    public function Task(){
        return $this->hasMany(Task::class, 'id_task', 'id');
    }
}
