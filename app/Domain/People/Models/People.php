<?php
namespace Chord\Domain\People\Models;

use Chord\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class People
 * @package Chord\Domain\People\Models
 */
class People extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}