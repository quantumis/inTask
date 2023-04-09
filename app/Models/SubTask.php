<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    public function Task(){
        return $this->belongsToMany(Task::class, 'sub_in__task', 'id_subtask', 'id_task');
    }
}
