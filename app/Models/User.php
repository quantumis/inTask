<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tg_name',
        'firstname',
        'lastname',
        'patronymic',
        'phone',
        'region',
        'birthdate',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Task(){
        return $this->belongsTo(Task::class, 'id', 'id_author');
    }

    public function Assigned(){
        return $this->belongsTo(Assigned::class, 'id', 'id_user');
    }

    public function Access(){
        return $this->belongsTo(Access::class, 'id', 'id_user');
    }

    public function Board(){
        return $this->belongsTo(Board::class, 'id', 'id_creator');
    }
}
