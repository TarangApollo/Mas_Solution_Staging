<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function cityDetail(Request $request)
    {
        if(Auth::User()->role_id == 1){
            $statemasters = DB::table('statemaster')
            ->where(['isDelete' => 0, "iStatus" => 1])
            ->orderBy('strStateName', 'ASC')
            ->get();
            return view('admin.city.index',compact('statemasters'));
        } else {
            return redirect()->route('home');
        }
    }

    public function addCity(Request $request)
    {
        if(Auth::User()->role_id == 1){

            $faqArr = array(
                "iStateId" => $request->iStateId,
                "strCityName" => $request->strCityName ,
                "strEntryDate" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            City::create($faqArr);
            Session::flash('Success', 'City Added Succefully!');
            return redirect()->route('admincity.cityDetail');
        } else {
            return redirect()->route('home');
        }
    }

    public function downloadCity(Request $request)
    {
        if(Auth::User()->role_id == 1){

            $cityList = City::select('strCityName', 'statemaster.strStateName')
            ->orderBy('iCityId', 'ASC')
            ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'citymaster.iStateId')
            ->get();
            return view('admin.city.download', compact('cityList'));
        } else {
            return redirect()->route('home');
        }
    }


}
