<?php

namespace App\Search;

use App\Advert;
use Elasticsearch\Client;

class ElasticsearchObserver
{
    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function saved($model)
    {
        // 6.14.1 only for those ads that are in the "Active" status.
        if($model->IsActive()){
            $this->elasticsearch->index([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
                'body' => $model->toSearchArray(),
            ]);
        }
    }

    public function deleted($model)
    {
        $this->elasticsearch->delete([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->getKey(),
        ]);
    }
}
