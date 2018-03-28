<?php

namespace Chord\Domain\Postcode\Repositories;

use Chord\App\Repository;
use Chord\Domain\Postcode\Models\Busstop;

class BusstopRepository extends Repository
{
    /**
     * BusstopRepository constructor.
     * @param Busstop $model
     */
    public function __construct(Busstop $model)
    {
        parent::__construct($model);
    }

    public function getClosestBusstops(float $longitude, float $latitude, int $limit = 5)
    {
        return $this->model->selectRaw(
            "id, name, ST_DISTANCE_SPHERE(POINT(lon, lat), POINT(?, ?)) as distance",
            [$longitude, $latitude])
            ->orderBy('distance', 'asc')
            ->limit($limit)->get();
    }
}