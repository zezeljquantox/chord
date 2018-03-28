<?php

namespace Chord\Domain\Postcode\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Busstop
 * @package Chord\Domain\Postcode\Models
 */
class Busstop extends Model
{
    public function scopeGetDistance(Builder $query, float $latitude, float $longitude)
    {
        return $query->whereRaw("ST_DISTANCE_SPHERE(
                                    POINT(longitude, latitude),
                                    point(?, ?)
                                )",
            [$longitude, $latitude]
        );
    }
}