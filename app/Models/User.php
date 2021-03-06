<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'middlename',
        'user_role_id',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'phone_access_time',
        'user_status_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function advert()
    {
        return $this->belongsTo(Advert::class, 'id', 'city_id');
    }

    public function moderation()
    {
        return $this->belongsTo(Moderation::class, 'id', 'user_id');
    }

    public function status()
    {
        return $this->hasOne(UserStatus::class, 'id', 'user_status_id');
    }

    public function role()
    {
        return $this->hasOne(UserRole::class, 'id', 'user_role_id');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail); // my notification
    }
}
