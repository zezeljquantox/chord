<?php

namespace Chord\Domain\Postcode\Services;

use Chord\App\Service;
use Chord\Domain\Postcode\Models\Postcode;
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

    public function getPostcodeDetailes(Postcode $postcode)
    {
        $addresses = $this->repository->getAddresses($postcode);
        $formateAddresses = $addresses->map(function ($address) {
            return $address->print();
        });
        dd($formateAddresses);
    }
}