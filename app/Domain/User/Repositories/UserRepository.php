<?php

namespace Chord\Domain\User\Repositories;

use Chord\App\Repository;
use Chord\Domain\User\Models\User;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}