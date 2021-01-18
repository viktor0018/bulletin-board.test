<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'user_id',
        'status',
        'description',
        'views',
        'price',
    ];

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function moderation()
    {
        return $this->belongsTo(Moderation::class, 'id', 'city_id');
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'id', 'city_id');
    }

    public function status()
    {
        return $this->hasOne(AdvertStatus::class, 'id', 'advert_status_id');
    }
}
