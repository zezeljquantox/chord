<?php

namespace Chord\Domain\Postcode\Repositories;

use Chord\App\Repository;
use Chord\Domain\Postcode\Models\Busstop;
use Illuminate\Contracts\Cache\Repository as Cache;

class BusstopRepository extends Repository
{
    private $cache;

    /**
     * BusstopRepository constructor.
     * @param Busstop $model
     */
    public function __construct(Cache $cache, Busstop $model)
    {
        parent::__construct($model);
        $this->cache = $cache;
    }

    public function getClosestBusstops(float $longitude, float $latitude, int $limit = 5)
    {
        $key = 'postcodes_' . $longitude . $latitude . '_busstops';

        if (!$this->cache->has($key)) {
            $busstops = $this->model->selectRaw(
                "id, name, ST_DISTANCE_SPHERE(POINT(lon, lat), POINT(?, ?)) as distance",
                [$longitude, $latitude])
                ->orderBy('distance', 'asc')
                ->limit($limit)->get();

            $this->cache->put($key, $busstops, 10);
            return $busstops;
        }

        return $this->cache->get($key);
    }
}