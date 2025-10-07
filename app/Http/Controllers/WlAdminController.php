<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class WlAdminController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->role_id == 2){
            return view('wladmin.index');
        } else {
            return redirect()->route('home'); 
        }
    }
}
