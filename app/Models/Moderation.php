<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moderation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'moderated_at',
        'resolution',
        'reason',
        'advert_id',
        'user_id'
    ];


    public function advert()
    {
        return $this->hasOne(Advert::class, 'id', 'advert_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
