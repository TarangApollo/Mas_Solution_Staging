<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketMaster;
use App\Models\TicketLog;
use App\Models\TicketDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;

class TopAnalyticReportController extends Controller
{
    public function index(){
        if(Auth::User()->role_id == 2){
            $ticketList = TicketMaster::select('ticketmaster.iCityId','ticketmaster.iStateId',DB::raw('count(ProjectName) as projectCount'),
                    DB::raw('count(ticketmaster.iTicketId) as issueCount'),DB::raw('count(ticketmaster.iCompanyId) as compaycount'),
                    DB::raw("(select citymaster.strCityName from citymaster where citymaster.iCityId=ticketmaster.iCityId) as strCityName"))
                ->where(['isDelete' => 0,'iStatus' => 1])
                ->where('ticketmaster.iCityId','!=','0')
                ->where('ticketmaster.OemCompannyId','=',Session::get('CompanyId'))
                ->groupBy('ticketmaster.iCityId')
                ->orderBy('issueCount','desc')
                //->take(5)
                ->get();
            return view('wladmin.topAnalytic.index',compact('ticketList'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function topCompanies(){
        if(Auth::User()->role_id == 2){
            $ticketList = TicketMaster::select('ticketmaster.iCompanyId',
                    DB::raw('count(ticketmaster.iTicketId) as issueCount'),DB::raw('count(ticketmaster.iCompanyId) as compaycount'),
                    DB::raw("(select companyclient.CompanyName from companyclient where companyclient.iCompanyClientId=ticketmaster.iCompanyId) as CompanyName"))
                ->where(['isDelete' => 0,'iStatus' => 1])
                ->where('ticketmaster.OemCompannyId','=',Session::get('CompanyId'))
                ->where('ticketmaster.iCompanyId','!=','0')
                ->groupBy('ticketmaster.iCompanyId')
                //->orderBy('CompanyName','asc')
                ->orderBy('issueCount','desc')
                ->get();
            return view('wladmin.topAnalytic.topCompanies',compact('ticketList'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function topSystems(){
        if(Auth::User()->role_id == 2){
            $ticketList = TicketMaster::select('ticketmaster.iSystemId','ticketmaster.iComnentId','ticketmaster.iSubComponentId',
                    DB::raw('count(*) as issueCount'),
                    DB::raw("(select strSystem from `system` where `system`.iSystemId=ticketmaster.iSystemId) as strSystem"),
                    DB::raw("(select strComponent from component where component.iComponentId=ticketmaster.iComnentId) as strComponent"),
                    DB::raw("(select strSubComponent from subcomponent where subcomponent.iSubComponentId=ticketmaster.iSubComponentId) as strSubComponent"))
                ->where(['isDelete' => 0,'iStatus' => 1])
                ->where('ticketmaster.iSystemId','!=','0')
                ->where('ticketmaster.OemCompannyId','=',Session::get('CompanyId'))
                ->groupBy('ticketmaster.iSystemId')
                ->orderBy('issueCount','desc')
                ->get();
            return view('wladmin.topAnalytic.topSystems',compact('ticketList'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function topCustomers(){
        if(Auth::User()->role_id == 2){
            $ticketList = TicketMaster::select(DB::raw('DISTINCT ticketmaster.CustomerMobile'),'CustomerName as strCustomerName',DB::raw('count(iTicketId) as issueCount'),'ticketmaster.CallerCompetencyId',
                    DB::raw("(select count(*) from ticketdetail where ticketdetail.iTicketId=ticketmaster.iTicketId) as 'CallCount'"),
                    'companyclient.CompanyName')
                ->join('companyclient','companyclient.iCompanyClientId','=','ticketmaster.iCompanyId')
                ->where(['ticketmaster.isDelete' => 0,'ticketmaster.iStatus' => 1])
                ->where('ticketmaster.OemCompannyId','=',Session::get('CompanyId'))
                ->whereNotNull('ticketmaster.CustomerName')
                ->groupBy('ticketmaster.CustomerMobile')
                ->orderBy('issueCount','desc')
                ->get();
            return view('wladmin.topAnalytic.topCustomers',compact('ticketList'));
        } else {
            return redirect()->route('home'); 
        }
    }
}
