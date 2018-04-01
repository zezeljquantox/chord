<?php
namespace Chord\Domain\Postcode\Repositories;

use Chord\App\Repository;
use Chord\Domain\Postcode\Models\Postcode;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * Class PostcodeRepository
 * @package Chord
 */
class PostcodeRepository extends Repository
{

    private $cache;

    /**
     * PostcodeRepository constructor.
     * @param Postcode $model
     */
    public function __construct(Cache $cache, Postcode $model)
    {
        parent::__construct($model);
        $this->cache = $cache;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        if (!$this->cache->has('postcodes')) {
            $postcodes = $this->model->select('id', 'postcode')->orderBy('postcode')->get();
            $this->cache->put('postcodes', $postcodes, 10);
            return $postcodes;
        }

        return $this->cache->get('postcodes');
    }

    /**
     * @param Postcode $postcode
     * @return mixed
     */
    public function getAddresses(Postcode $postcode)
    {
        $key = 'postcodes_' . $postcode->id . '_addresses';

        if (!$this->cache->has($key)) {
            $addresses = $postcode->addresses;
            $this->cache->put($key, $addresses, 10);
            return $addresses;
        }
        return $this->cache->get($key);
    }

}