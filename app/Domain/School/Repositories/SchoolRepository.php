<?php

namespace Chord\Domain\School\Repositories;

use Chord\App\Repository;
use Chord\Domain\School\Models\School;

class SchoolRepository extends Repository
{
    /**
     * SchoolRepository constructor.
     * @param School $model
     */
    public function __construct(School $model)
    {
        parent::__construct($model);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @param int $range
     * @return mixed
     */
    public function getSchoolsInRange(float $longitude, float $latitude, int $range = 10)
    {
        return $this->model->join('postcodes', 'schools.postcode_id', '=', 'postcodes.id')
                ->selectRaw('schools.id, schools.name, ST_DISTANCE_SPHERE(POINT(postcodes.longitude, postcodes.latitude), POINT(?, ?)) * .000621371192 as distance',
                    [$longitude, $latitude])
                ->groupBy('schools.id')
                ->having('distance', '<', $range)
                ->get();
    }
}