<?php

namespace Chord\Domain\School\Repositories;

use Chord\App\Repository;
use Chord\Domain\School\Models\School;
use Illuminate\Contracts\Cache\Repository as Cache;

class SchoolRepository extends Repository
{
    private $cache;

    /**
     * SchoolRepository constructor.
     * @param Cache $cache
     * @param School $model
     */
    public function __construct(Cache $cache, School $model)
    {
        parent::__construct($model);
        $this->cache = $cache;
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @param int $range
     * @return mixed
     */
    public function getSchoolsInRange(float $longitude, float $latitude, int $range = 10)
    {
        $key = 'postcodes_' . $longitude . $latitude . '_schools';

        if (!$this->cache->has($key)) {
            $schools = $this->model->join('postcodes', 'schools.postcode_id', '=', 'postcodes.id')
                ->selectRaw('schools.id, schools.name, ST_DISTANCE_SPHERE(POINT(postcodes.longitude, postcodes.latitude), POINT(?, ?)) * .000621371192 as distance',
                    [$longitude, $latitude])
                ->groupBy('schools.id')
                ->having('distance', '<', $range)
                ->get();

            $this->cache->put($key, $schools, 10);
            return $schools;
        }

        return $this->cache->get($key);
    }
}