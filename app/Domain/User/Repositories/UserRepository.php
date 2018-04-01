<?php

namespace Chord\Domain\User\Repositories;

use Chord\App\Repository;
use Chord\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRepository
 * @package Chord\Domain\User\Repositories
 */
class UserRepository extends Repository
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->model->find($id);
    }
}