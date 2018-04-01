<?php

namespace Chord\Domain\User\Repositories;

use Chord\App\Repository;
use Chord\Domain\User\Models\Chat;
use Illuminate\Support\Facades\DB;

class ChatRepository extends Repository
{
    /**
     * ChatRepository constructor.
     * @param Chat $model
     */
    public function __construct(Chat $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $userA
     * @param int $userB
     * @return mixed
     */
    public function getChat(int $userA, int $userB)
    {
        return $this->model->select('from', 'to', 'message')->where(function ($query) use ($userA, $userB) {
            $query->where('to', $userA)
                ->where('from', $userB);
        })
            ->orWhere(function ($query) use ($userA, $userB) {
                $query->where('to', $userB)
                    ->where('from', $userA);
            })
            ->orderBy('date', 'asc')
            ->take(30)->get();

    }
}