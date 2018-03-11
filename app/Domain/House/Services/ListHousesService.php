<?php

namespace Chord\Domain\House\Services;


use Chord\App\Service;
use Chord\Domain\House\Repositories\HouseRepository;
use Illuminate\Database\Eloquent\Collection;
use Facades\Chord\Domain\User\Repositories\ReactionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListHousesService extends Service
{
    public function __construct(HouseRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getHouses($numOfItems = 20): LengthAwarePaginator
    {
        return $this->repository->load($numOfItems);
    }

    public function getReactions($userId, array $userIds): Collection
    {
       return ReactionRepository::getUsersReaction($userId, $userIds);
    }
}