<?php

namespace Chord\Domain\House\Services;

use Chord\App\Service;
use Chord\Domain\House\Repositories\HouseRepository;
use Chord\Domain\User\Models\User;
use Facades\Chord\Domain\User\Repositories\UserRepository;

/**
 * Class SwapHouseService
 * @package Chord\Domain\House\Services
 */
class SwapHouseService extends Service
{
    /**
     * SwapHouseService constructor.
     * @param HouseRepository $repository
     */
    public function __construct(HouseRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param User $userA
     * @param int $userB
     * @return array
     */
    public function swapHouses(User $userA, int $userB): array
    {
        $userB = UserRepository::find($userB);

        return $this->repository->swapHouses($userA, $userB);

    }
}