<?php

namespace App\Articles;

use App\Models\Advert;
use Illuminate\Database\Eloquent\Collection;

class EloquentRepository implements ArticlesRepository
{
    public function search(string $query = ''): Collection
    {
        return Advert::query()
            ->where('description', 'like', "%{$query}%")
            ->orWhere('title', 'like', "%{$query}%")
            ->get();
    }
}
