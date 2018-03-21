<?php

namespace Chord\Domain\User\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $table = "likes";
    protected $fillable = ['a', 'b', 'like'];
    const UPDATED_AT = 'date';

    public function setCreatedAt($value)
    {
        return NULL;
    }

    public function userA()
    {
        return $this->belongsTo(User::class, 'a');
    }

    public function userB()
    {
        return $this->belongsTo(User::class, 'b');
    }
}