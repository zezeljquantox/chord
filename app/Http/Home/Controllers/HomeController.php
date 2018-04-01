<?php

namespace Chord\Http\Home\Controllers;

use Chord\Domain\User\Services\UserCsvService;
use Chord\Http\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        return view('welcome');
    }

    /**
     * @param UserCsvService $service
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getUsersCsv(UserCsvService $service)
    {
        $file = $service->isExistsCronCsv();
        if($file){
            return response()->download($file, 'users.csv');
        }

        $file = $service->getCsv();
        return response()->download($file, 'users.csv')->deleteFileAfterSend(true);
    }
}
