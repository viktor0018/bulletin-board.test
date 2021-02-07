<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Search\Searchable;

class Advert extends Model
{
    use HasFactory, Searchable;

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
        'category_id',
        'advert_status_id',
        'city_id',
        'published_at'

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
        return $this->hasMany(Moderation::class, 'advert_id', 'id');
    }

    public function photo()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }

    public function status()
    {
        return $this->hasOne(AdvertStatus::class, 'id', 'advert_status_id');
    }


    public function isActive(){
        return $this->advert_status_id == 5;
    }
}
