<?php

namespace Chord\Domain\Address\Repositories;

use Chord\App\Repository;
use Chord\Domain\Address\Models\Address;

/**
 * Class AddressRepository
 * @package Chord\Domain\Address\Repositories
 */
class AddressRepository extends Repository
{
    /**
     * AddressRepository constructor.
     * @param Address $model
     */
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }
}