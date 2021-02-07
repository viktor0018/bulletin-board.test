<?php

namespace App\Adverts;

use Illuminate\Database\Eloquent\Collection;

interface AdvertsRepository
{
    public function search(string $query = ''): Collection;
}
