<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (true || env("SQL_DEBUG_LOG"))
        {
            DB::listen(function ($query) {
                //Log::debug("DB: " . $query->sql . "[".  implode(",",$query->bindings). "]");
            });
        }

        Validator::extend(
            'exists_or_null',
            function ($attribute, $value, $parameters)
            {
                if($value == 0 || is_null($value)) {
                    return true;
                } else {
                    $validator = Validator::make([$attribute => $value], [
                        $attribute => 'exists:' . implode(",", $parameters)
                    ]);
                    return !$validator->fails();
                }
            },'The selected category id is invalid.'
        );
    }
}
