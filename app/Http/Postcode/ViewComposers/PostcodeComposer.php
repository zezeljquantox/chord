<?php
namespace Chord\Http\Postcode\ViewComposers;

use Chord\Domain\Postcode\Services\PostcodeService;
use Illuminate\View\View;

/**
 * Class PostcodeComposer
 * @package Chord\Http\Postcode\ViewComposers
 */
class PostcodeComposer
{
    /**
     * @var PostcodeService
     */
    protected $service;

    /**
     * PostcodeComposer constructor.
     * @param PostcodeService $service
     */
    public function __construct(PostcodeService $service)
    {
        $this->service = $service;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('postcodes', $this->service->getAll());
    }
}