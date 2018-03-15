<?php
namespace Chord\Domain\User\Services;

use Chord\App\Service;
use Facades\Chord\Domain\House\Repositories\HouseRepository;
use Chord\Domain\User\Repositories\ReactionRepository;
use Illuminate\Database\Eloquent\Model;

class ReactionService extends Service
{
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

}