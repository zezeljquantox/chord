<?php

namespace Chord\Domain\Postcode\Services;

use Chord\App\Service;
use Chord\Domain\Postcode\Repositories\PostcodeRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PostcodeService
 * @package Chord\Domain\Postcode\Services
 */
class PostcodeService extends Service
{
    /**
     * PostcodeService constructor.
     * @param PostcodeRepository $repository
     */
    public function __construct(PostcodeRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll()->groupBy(function ($postcode, $key) {
            return explode(' ', $postcode['postcode'])[0];
        });
    }
}