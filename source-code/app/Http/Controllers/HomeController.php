<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\UpdateRepository;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.welcome.home');
    }

    public function getUserHome(Request $request, UpdateRepository $updateRepo)
    {
        $updates = $updateRepo->getFeeds(Auth::user()->id);

        if($request->ajax() === true) return view('frontend.partials.updates', ['updates' => $updates]);
        return view('frontend.user.home', ['updates' => $updates]);
    }
}
