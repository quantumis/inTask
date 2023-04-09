<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_in_Board extends Model
{
    use HasFactory;

    public function Board(){
        return $this->hasMany(Board::class, 'id_board', 'id');
    }

    public function Task(){
        return $this->hasMany(Task::class, 'id_task', 'id');
    }
}
