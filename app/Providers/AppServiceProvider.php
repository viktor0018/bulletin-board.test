<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Adverts;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Articles\ArticlesRepository::class, function ($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            if (! config('services.search.enabled')) {
                return new EloquentRepository();
            }

            return new ElasticsearchRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
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
