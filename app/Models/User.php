<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'user_id',
        'points'
    ];
    
    public function indicated_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function indicated()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
