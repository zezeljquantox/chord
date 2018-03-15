<?php

namespace Chord\Domain\House\Repositories;

use Chord\App\Repository;
use Chord\Domain\House\Models\House;
use Illuminate\Pagination\LengthAwarePaginator;

class HouseRepository extends Repository
{
    public function __construct(House $model)
    {
        parent::__construct($model);
    }

    public function load($numOfItems): LengthAwarePaginator
    {
        return $this->model->with(['address', 'user'])->paginate($numOfItems);
    }

    public function getUser(int $houseId)
    {
        return $this->model->findOrFail($houseId)->user;
    }
}