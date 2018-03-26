<?php
namespace Chord\Domain\Postcode\Repositories;

use Chord\App\Repository;
use Chord\Domain\Postcode\Models\Postcode;

/**
 * Class PostcodeRepository
 * @package Chord
 */
class PostcodeRepository extends Repository
{
    /**
     * PostcodeRepository constructor.
     * @param Postcode $model
     */
    public function __construct(Postcode $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        return $this->model->select('id', 'postcode')->orderBy('postcode')->get();
    }

    public function getAddresses(Postcode $postcode)
    {
        return $postcode->addresses;
    }
}