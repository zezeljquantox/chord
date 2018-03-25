<?php

namespace Chord\Domain\User\Services;

use Chord\App\Service;
use Chord\Domain\User\Repositories\ChatRepository;

class ChatService extends Service
{
    /**
     * ChatService constructor.
     * @param ChatRepository $repository
     */
    public function __construct(ChatRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param int $userA
     * @param int $userB
     * @return mixed
     */
    public function getChat(int $userA, int $userB)
    {
        return $this->repository->getChat($userA, $userB);
    }
}