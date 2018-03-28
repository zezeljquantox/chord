<?php

namespace Chord\Domain\School\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public function postcode()
    {
        return $this->belongsTo(Postcode::class);
    }
}