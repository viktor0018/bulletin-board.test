<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_main',
        'advert_id',
        'link',
    ];

    public function advert()
    {
        return $this->hasOne(Advert::class, 'id', 'advert_id');
    }
}
