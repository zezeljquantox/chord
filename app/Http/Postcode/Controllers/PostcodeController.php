<?php
namespace Chord\Http\Postcode\Controllers;

use Chord\Domain\Postcode\Models\Postcode;
use Chord\Domain\Postcode\Services\PostcodeService;
use Chord\Http\Controller;

class PostcodeController extends Controller
{
    /**
     * @var PostcodeService
     */
    private $service;

    /**
     * PostcodeController constructor.
     * @param PostcodeService $service
     */
    public function __construct(PostcodeService $service)
    {
        $this->service = $service;
    }

    public function index(Postcode $postcode)
    {
        $addresses = $this->service->getPostcodeDetailes($postcode);
        dd($addresses->toArray());
    }
}