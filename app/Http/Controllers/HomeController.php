<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

    public function myUploads(Request $request)
    {
        $uploads = $request->user()->uploads()->orderBy('id', 'DESC')->paginate(20);

        return view('uploads', [
            'uploads' => $uploads,
        ]);
    }
}
