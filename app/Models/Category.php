<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'id', 'parent_id');
    }

    public function advert()
    {
        return $this->hasMany(Advert::class);
    }
}
