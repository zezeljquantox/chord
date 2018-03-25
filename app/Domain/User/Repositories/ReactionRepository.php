<?php

namespace Chord\Domain\User\Repositories;

use Chord\App\Repository;
use Chord\Domain\User\Models\Reaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReactionRepository
 * @package Chord\Domain\User\Repositories
 */
class ReactionRepository extends Repository
{
    /**
     * ReactionRepository constructor.
     * @param Reaction $model
     */
    public function __construct(Reaction $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $userId
     * @param array $userIds
     * @return Collection
     */
    public function getUsersReaction(int $userId, array $userIds): Collection
    {
        return $this->model->where(function ($query) use ($userId, $userIds) {
            $query->where('a', $userId)
                ->whereIn('b', $userIds);
        })
            ->orWhere(function ($query) use ($userId, $userIds) {
                $query->whereIn('a', $userIds)
                    ->where('b', $userId);
            })->get();
    }

    /**
     * @param int $userA
     * @param int $userB
     * @param int $action
     * @return Model
     */
    public function createOrUpdate(int $userA, int $userB, int $action): Model
    {
        return $this->model->updateOrCreate(
            ['a' => $userA, 'b' => $userB],
            ['like' => $action]
        );
    }

    /**
     * @param int $userA
     * @param int $userB
     * @return mixed
     */
    public function getReaction(int $userA, int $userB)
    {
        return $this->model->where('a', $userA)->where('b', $userB)->first();
    }

    /**
     * @param int $userA
     * @param int $userB
     * @return bool
     */
    public function removeByUsers(int $userA, int $userB): bool
    {
        return $this->model->where('a', $userA)->where('b', $userB)->delete();
    }

}