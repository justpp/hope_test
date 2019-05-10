<?php

namespace App\Http\Controllers;

use App\User;
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function userList()
    {
        $users = User::paginate(5);

        return view('users',compact('users'));
    }

    public function userData()
    {
        $user = User::all()->toArray();

        return $this->jsonResult($user);
    }
}
