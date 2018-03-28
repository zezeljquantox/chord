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
        $busstops = $this->service->getClosestBusstops($postcode);
        $schools = $this->service->getSchoolsInRange($postcode);

        return response()->json(['addresses' => collect($addresses), 'busstops' => $busstops, 'schools' => $schools]);
    }
}