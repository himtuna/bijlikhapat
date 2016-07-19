<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::guest()) {
            return view('welcome');    
        }
        else 
        {
            return redirect('connections');
        }
        
    }

    public function home()
    {
        return view('home');
    }
}
