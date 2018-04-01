<?php

namespace Chord\Domain\House\Models;

use Chord\Domain\Address\Models\Address;
use Chord\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    const UPDATED_AT = 'updated';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}