<?php

namespace Chord\Domain\User\Repositories;

use Chord\App\Repository;
use Chord\Domain\User\Models\Reaction;

class ReactionRepository extends Repository
{
    public function __construct(Reaction $model)
    {
        parent::__construct($model);
    }

    public function getUsersReaction($userId, array $userIds)
    {
        //return $this->model->whereIn('a', $userIds)->get();

        return $this->model->where(function ($query) use ($userId, $userIds){
            $query->where('a', $userId)
                  ->whereIn('b', $userIds);
        })
            ->orWhere(function ($query) use ($userId, $userIds){
                $query->whereIn('a', $userIds)
                    ->where('b', $userId);
            })->get();
    }
}