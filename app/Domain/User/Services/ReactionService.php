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
            /*
            if($reaction->like == 1 && $oppositeReaction->like ==1){
                return 'UserLikedHouse';
            } else {
                return
            }
            */
            return "Chord\\Domain\\User\\Events\\UserReactedOnHouse";
        }
        return '';
    }

    public function remove(int $houseId, int $userId)
    {
        $house = HouseRepository::find($houseId);
        dd($house);
        $record = $this->repository->getReaction($userId, $house->user);
        $this->repository->remove($record);

        return $record;
    }

}