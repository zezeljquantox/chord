<?php

namespace Chord\Domain\Postcode\Services;

use Chord\App\Service;
use Chord\Domain\Postcode\Models\Postcode;
use Chord\Domain\Postcode\Repositories\PostcodeRepository;
use Facades\Chord\Domain\Postcode\Repositories\BusstopRepository;
use Facades\Chord\Domain\School\Repositories\SchoolRepository;
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

    public function getPostcodeDetailes(Postcode $postcode)
    {
        $addresses = $this->repository->getAddresses($postcode);
        $formatedAddresses = $addresses->reduce(function ($formatedAddresses, $address) {
            $formatedAddresses[$address->id]['name'] = $address->full_address;
            return $formatedAddresses;
        });

        return $formatedAddresses;
    }

    /**
     * @param Postcode $postcode
     * @return mixed
     */
    public function getClosestBusstops(Postcode $postcode)
    {
        return BusstopRepository::getClosestBusstops($postcode->longitude, $postcode->latitude);
    }

    /**
     * @param Postcode $postcode
     * @return mixed
     */
    public function getSchoolsInRange(Postcode $postcode)
    {
        return SchoolRepository::getSchoolsInRange($postcode->longitude, $postcode->latitude);
    }
}