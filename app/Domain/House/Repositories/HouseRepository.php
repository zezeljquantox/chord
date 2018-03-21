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

    public function load(int $userId, $numOfItems): LengthAwarePaginator
    {
        return $this->model->with(['address', 'user'])->where('user_id','!=', $userId)->paginate($numOfItems);
    }

    public function getUser(int $houseId)
    {
        return $this->model->findOrFail($houseId)->user;
    }

    public function find(int $houseId)
    {
        return $this->model->with('user')->find($houseId);
    }
}