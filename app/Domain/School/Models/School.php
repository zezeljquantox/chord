<?php

namespace Chord\Domain\School\Models;

use Chord\Domain\Postcode\Models\Postcode;
use Illuminate\Database\Eloquent\Model;

/**
 * Class School
 * @package Chord\Domain\School\Models
 */
class School extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postcode()
    {
        return $this->belongsTo(Postcode::class);
    }
}