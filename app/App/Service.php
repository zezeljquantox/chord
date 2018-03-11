<?php
namespace Chord\App;

abstract class Service
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Service constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
}