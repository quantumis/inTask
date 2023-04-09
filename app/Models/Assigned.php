<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigned extends Model
{
    use HasFactory;

    public function User(){
        return $this->hasMany(User::class, 'id_user', 'id');
    }

    public function Task(){
        return $this->hasMany(Task::class, 'id_task', 'id');
    }
}
