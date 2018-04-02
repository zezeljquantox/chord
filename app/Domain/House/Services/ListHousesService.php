<?php

namespace Chord\Domain\House\Services;

use Chord\App\Service;
use Chord\Domain\House\Repositories\HouseRepository;
use Illuminate\Database\Eloquent\Collection;
use Facades\Chord\Domain\User\Repositories\ReactionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListHousesService extends Service
{
    /**
     * ListHousesService constructor.
     * @param HouseRepository $repository
     */
    public function __construct(HouseRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param int $userId
     * @param int $numOfItems
     * @return LengthAwarePaginator
     */
    public function getHouses(int $userId, int $numOfItems = 20): LengthAwarePaginator
    {
        return $this->repository->load($userId, $numOfItems);
    }

    /**
     * @param int $userId
     * @param array $userIds
     * @return Collection
     */
    public function getReactions(int $userId, array $userIds): Collection
    {
       return ReactionRepository::getUsersReaction($userId, $userIds);
    }

    //unused
    public function getHousesWithReactions(int $userId)
    {
        $houses = $this->getHouses();
        $userIds = $houses->pluck('user_id')->toArray();

        $reactions = $this->getReactions($userId, $userIds);

        $res = $this->mergeHousesAndReactions($houses->items(), $reactions);
    }

    /**
     * @param $userId
     * @param $reactions
     * @return array
     */
    public function mapHousesReactions($userId, $reactions)
    {
        $mapedReactions = [];

        $reactions->each(function ($reaction) use ($userId, &$mapedReactions) {
            if($userId == $reaction->a){
                if($reaction->like){
                    $mapedReactions[$reaction->b]['like'] = 'btn-success';
                } else {
                    $mapedReactions[$reaction->b]['dislike'] = 'btn-danger';
                }
            } elseif ($userId == $reaction->b){
                $mapedReactions[$reaction->a]['likedByOther'] = $reaction->like ? 'btn-success' : '';
            }
        });

        return $mapedReactions;
    }
}