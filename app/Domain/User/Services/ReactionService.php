<?php
namespace Chord\Domain\User\Services;

use Chord\App\Service;
use Chord\Domain\User\Models\Reaction;
use Facades\Chord\Domain\House\Repositories\HouseRepository;
use Chord\Domain\User\Repositories\ReactionRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReactionService
 * @package Chord\Domain\User\Services
 */
class ReactionService extends Service
{
    /**
     * ReactionService constructor.
     * @param ReactionRepository $repository
     */
    public function __construct(ReactionRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param int $house
     * @param int $action
     * @param int $userId
     * @return Model
     */
    public function create(int $house, int $action, int $userId): Model
    {
        $user = HouseRepository::getUser($house);
        return $this->repository->createOrUpdate($userId, $user->id, $action);
    }

    public function isChatAvailable(Reaction $reaction)
    {
        $oppositeReaction = $this->repository->getReaction($reaction->b, $reaction->a);
        //var_dump($oppositeReaction);
        if($oppositeReaction){
            if($reaction->like == 1 && $oppositeReaction->like == 1){
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $houseId
     * @param int $userId
     * @return int
     */
    public function remove(int $houseId, int $userId): int
    {
        $house = HouseRepository::find($houseId);
        $this->repository->removeByUsers($userId, $house->user->id);
        return $house->user->id;
    }

}