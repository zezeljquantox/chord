<?php

namespace Chord\Domain\Postcode\Models;

use Chord\Domain\Address\Models\Address;
use Chord\Domain\School\Models\School;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Postcode
 * @package Chord\Domain\Postcode\Models
 */
class Postcode extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function getSchoolsInRange(int $range = 10)
    {
        return $this->schools()->whereRaw("ST_DISTANCE_SPHERE(POINT(postcodes.longitude, postcodes.latitude), POINT(?, ?)) * .000621371192 < ?",
            [$this->longitude, $this->latitude, $range])->get()->count();
    }
}
