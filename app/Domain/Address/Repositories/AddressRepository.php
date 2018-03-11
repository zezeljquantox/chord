<?php

namespace Chord\Domain\Address\Repositories;

use App\Repository;
use Chord\Domain\Address\Models\Address;

/**
 * Class AddressRepository
 * @package Chord\Domain\Address\Repositories
 */
class AddressRepository extends Repository
{
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }
}