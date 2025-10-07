<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;


class CallListController extends Controller
{
    public function index(Request $request)
    {
        return view('wladmin.callList.index');
    }

    public function info()
    {
        return view('wladmin.callList.info');
    }
}
