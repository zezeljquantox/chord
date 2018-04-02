<?php

namespace Chord\Domain\House\Repositories;

use Chord\App\Repository;
use Chord\Domain\House\Models\House;
use Chord\Domain\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Class HouseRepository
 * @package Chord\Domain\House\Repositories
 */
class HouseRepository extends Repository
{
    /**
     * HouseRepository constructor.
     * @param House $model
     */
    public function __construct(House $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $userId
     * @param $numOfItems
     * @return LengthAwarePaginator
     */
    public function load(int $userId, $numOfItems): LengthAwarePaginator
    {
        return $this->model->with(['address', 'user'])->where('user_id','!=', $userId)->paginate($numOfItems);
    }

    /**
     * @param int $houseId
     * @return mixed
     */
    public function getUser(int $houseId)
    {
        return $this->model->findOrFail($houseId)->user;
    }

    /**
     * @param int $houseId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function find(int $houseId)
    {
        return $this->model->with('user')->find($houseId);
    }

    /**
     * @param User $userA
     * @param User $userB
     * @return array
     */
    public function swapHouses(User $userA, User $userB): array
    {
        $houseA = $userA->house;
        $houseB = $userB->house;
        DB::transaction(function () use ($userA, $userB, $houseA, $houseB) {
            $userA->house()->save($houseB);
            $userB->house()->save($houseA);
        });

        return [$userA->house, $userB->house];
    }
}