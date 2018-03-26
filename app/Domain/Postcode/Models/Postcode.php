<?php

namespace Chord\Domain\Postcode\Models;

use Chord\Domain\Address\Models\Address;
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
}
