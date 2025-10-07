<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Loginlog;
use App\Models\CompanyMaster;
use App\Models\CallAttendent;
use App\Models\Component;
use App\Models\WlUser;
use App\Models\SubComponent;

class AdminDashboardController extends Controller
{
    public function index(Request $request){
        
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));

        $totalTicket = DB::table('ticketmaster')
                ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
                ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
                ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
                ->count();
        $openTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->where('finalStatus', '0')
            ->count();
        $custCompany = DB::table('companyclient')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('iCompanyId', $fCompany))
            ->where('iStatus', '1')
            ->where('isDelete', '0')
            ->count();

        $customer = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->groupBy('ticketmaster.CustomerMobile')
            ->get();
        $where = "1=1";
        if(isset($request->fMonth)){
            $where .= " and MONTH(ticketmaster.strEntryDate) = '".$request->fMonth."'";
        }
        if(isset($request->yearId)){
            $where .= " and ticketmaster.strEntryDate between '".$datefrom."' and '".$dateto."'";
        }
        if(isset($request->fCompany)){
            $where .= " and ticketmaster.OemCompannyId='".$request->fCompany."'";
        }
        $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
            (select count(*) as t1 from ticketmaster where
            ".$where." and ticketmaster.tataCallId != ''
            UNION ALL
            select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
            and ticketlog.tataCallId != '' and ".$where.") tbl");
        $ticketCall = $ticketCallQuery[0]->tCall;
        $dashboarCount = array('call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custCompany' => $custCompany, 'customer' => count($customer));
        
        return json_encode($dashboarCount);
    }

    public function getPiaChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
        
        $callLog = DB::table('ticketmaster')
            ->select(DB::raw('count(ticketmaster.iTicketId) as issueCount'),DB::raw("(select strSystem from `system` where `system`.iSystemId=ticketmaster.iSystemId) as strSystem"))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('ticketmaster.strEntryDate','=', $fMonth))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->orderBy('strSystem', 'ASC')
            ->groupBy('ticketmaster.iSystemId')
            ->get();
        
        return json_encode($callLog);
    }

    public function getBarChart(Request $request){
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));

        $callLog = DB::table('ticketmaster')
            ->select(DB::raw("DAY(strEntryDate) as `monthdays`"), DB::raw('count(*) as `total`'))
            //->where('OemCompannyId', Session::get('CompanyId'))
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy(DB::raw("DAY(strEntryDate)"), 'ASC')
            ->groupBy(DB::raw("DAY(strEntryDate)"))
            ->get();
        $dayCount = 0;
        if($request->fMonth > 12){
            $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->endDate))));
        } else {
            $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->startDate))));
        }
        $montharray = array();
        for($iCounter = 1; $iCounter <= $dayCount; $iCounter++){
            $montharray[$iCounter] = 0;    
        }
        //dd($montharray);
        //$montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $totalSum = 0;
        foreach ($callLog as $call) {
            if (array_key_exists($call->monthdays, $montharray)) {
                $totalSum =   (int)$totalSum + (int)$call->total;
            }
        }
        foreach ($callLog as $call) {
            if (array_key_exists($call->monthdays, $montharray)) {
                //$montharray[$call->month] = ($call->total / 100) * $totalSum;
                $montharray[$call->monthdays] = $call->total;
                $call->total;
            }
        }
        $dataArr = array();
        foreach($montharray as $key => $val)
        {
            $dataArr[] = array(
                "Count" => $val,
                "key" => $key
            );
        }
        //dd($dataArr);
        return json_encode($dataArr);
    }

    public function getLineChart(Request $request){
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
        $calllevel = DB::table('ticketmaster')
            ->select(DB::raw("DAY(strEntryDate) as `monthdays`"), DB::raw('count(*) as `total`'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1,2,4))
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy(DB::raw("DAY(strEntryDate)"), 'ASC')
            ->groupBy(DB::raw("DAY(strEntryDate)"))
            ->get();
        $totalSum = DB::table('ticketmaster')->select(DB::raw("count(*) as `total`"))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->count();
        
        //$montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $dayCount = 0;
        if($request->fMonth > 12){
            $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->endDate))));
        } else {
            $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->startDate))));
        }
        $montharray = array();
        for($iCounter = 1; $iCounter <= $dayCount; $iCounter++){
            $montharray[$iCounter] = 0;    
        }
        $month = '';
        foreach ($calllevel as $call) {
            if (array_key_exists($call->monthdays, $montharray)) {
                //$montharray[$call->monthdays] = round(($call->total * 100) / $totalSum,2);
                $montharray[$call->monthdays] = $call->total;
            }
        }
        $dataArr = array();
        foreach($montharray as $key => $val)
        {
            $dataArr[] = array(
                "Count" => $val,
                "key" => $key
            );
        }
        /********************************************* */
        $calllevelTwo = DB::table('ticketmaster')
            ->select(DB::raw("DAY(strEntryDate) as `monthdays`"), DB::raw('count(*) as `total`'))
            ->where('LevelId', '2')
            //->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1,2,4))
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy(DB::raw("DAY(strEntryDate)"), 'ASC')
            ->groupBy(DB::raw("DAY(strEntryDate)"))
            ->get();
        $monthlevelTwoarray = array();
        for($iCounter = 1; $iCounter <= $dayCount; $iCounter++){
            $monthlevelTwoarray[$iCounter] = 0;    
        }
        foreach ($calllevelTwo as $call) {
            if (array_key_exists($call->monthdays, $monthlevelTwoarray)) {
                //$monthlevelTwoarray[$call->monthdays] = round(($call->total * 100) / $totalSum,2);
                $monthlevelTwoarray[$call->monthdays] = $call->total;
            }
        }

        $callsamedayresolved = DB::table('ticketmaster')
            ->select(DB::raw("DAY(strEntryDate) as `monthdays`"), DB::raw('count(*) as `total`'))
            //->where('OemCompannyId', Session::get('CompanyId'))
            // ->where('LevelId', '1')
            // ->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1,2,4))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->where('ComplainDate','>=',DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'))
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
            ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
            ->orderBy(DB::raw("DAY(strEntryDate)"), 'ASC')
            ->groupBy(DB::raw("DAY(strEntryDate)"))
            ->get();
        $monthsamedayresolvedarray = array();
        for($iCounter = 1; $iCounter <= $dayCount; $iCounter++){
            $monthsamedayresolvedarray[$iCounter] = 0;    
        }
        $month = '';
        foreach ($calllevel as $call) {
            if (array_key_exists($call->monthdays, $monthsamedayresolvedarray)) {
                //$monthsamedayresolvedarray[$call->monthdays] = round(($call->total * 100) / $totalSum,2);
                $monthsamedayresolvedarray[$call->monthdays] = $call->total;
            }
        }
        $allDataArr = array(
            "levelOne" => $dataArr,
            "levelTwo" => $monthlevelTwoarray,
            "samedaysolution" => $monthsamedayresolvedarray,
        );
        //dd(json_encode($allDataArr));
        return json_encode($allDataArr);
    }

    // public function getLineChartLevelTwo(Request $request){
    //     $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
    //     $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
    //     $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
    //     $calllevel = DB::table('ticketmaster')
    //         ->select(DB::raw("DAY(strEntryDate) as `monthdays`"), DB::raw('count(*) as `total`'))
    //         ->where('LevelId', '2')
    //         //->where('iLevel2CallAttendentId', '0')
    //         ->whereIn('finalStatus', array(1,2,4))
    //         ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
    //         ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
    //         ->whereBetween('strEntryDate', [$datefrom, $dateto])
    //         ->orderBy(DB::raw("DAY(strEntryDate)"), 'ASC')
    //         ->groupBy(DB::raw("DAY(strEntryDate)"))
    //         ->get();
    //     $totalSum = DB::table('ticketmaster')->select(DB::raw("count(*) as `total`"))
    //         ->where('LevelId', '2')
    //         ->whereBetween('strEntryDate', [$datefrom, $dateto])
    //         ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
    //         ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
    //         ->count();
        
    //     //$montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     $dayCount = 0;
    //     if($request->fMonth > 12){
    //         $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->endDate))));
    //     } else {
    //         $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->startDate))));
    //     }
    //     $montharray = array();
    //     for($iCounter = 1; $iCounter <= $dayCount; $iCounter++){
    //         $montharray[$iCounter] = 0;    
    //     }
    //     $month = '';
    //     foreach ($calllevel as $call) {
    //         if (array_key_exists($call->monthdays, $montharray)) {
    //             $montharray[$call->monthdays] = round(($call->total * 100) / $totalSum,2);
    //         }
    //     }
    //     $dataArr = array();
    //     foreach($montharray as $key => $val)
    //     {
    //         $dataArr[] = array(
    //             "Count" => $val,
    //             "key" => $key
    //         );
    //     }
    //     return json_encode($dataArr);
    // }

    // public function getLineChartSameDayResolve(Request $request){
    //     $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
    //     $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
    //     $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
    //     $calllevel = DB::table('ticketmaster')
    //         ->select(DB::raw("DAY(strEntryDate) as `monthdays`"), DB::raw('count(*) as `total`'))
    //         //->where('OemCompannyId', Session::get('CompanyId'))
    //         // ->where('LevelId', '1')
    //         // ->where('iLevel2CallAttendentId', '0')
    //         ->whereIn('finalStatus', array(1,2,4))
    //         ->whereBetween('strEntryDate', [$datefrom, $dateto])
    //         ->where('ComplainDate','>=',DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'))
    //         ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
    //         ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
    //         ->orderBy(DB::raw("DAY(strEntryDate)"), 'ASC')
    //         ->groupBy(DB::raw("DAY(strEntryDate)"))
    //         ->get();
    //     $totalSum = DB::table('ticketmaster')->select(DB::raw("count(*) as `total`"))
    //         //->where('OemCompannyId', Session::get('CompanyId'))
    //         ->where('LevelId', '1')
    //         ->where('iLevel2CallAttendentId', '0')
    //         ->whereBetween('strEntryDate', [$datefrom, $dateto])
    //         ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate','=', $fMonth))
    //         ->when($request->fCompany, fn ($query, $fCompany) => $query->where('OemCompannyId', $fCompany))
    //         ->count();
        
    //     //$montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     $dayCount = 0;
    //     if($request->fMonth > 12){
    //         $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->endDate))));
    //     } else {
    //         $dayCount = date('t',strtotime(date('Y-'.$request->fMonth.'-d', strtotime($yeardetail->startDate))));
    //     }
    //     $montharray = array();
    //     for($iCounter = 1; $iCounter <= $dayCount; $iCounter++){
    //         $montharray[$iCounter] = 0;    
    //     }
    //     $month = '';
    //     foreach ($calllevel as $call) {
    //         if (array_key_exists($call->monthdays, $montharray)) {
    //             $montharray[$call->monthdays] = round(($call->total * 100) / $totalSum,2);
    //         }
    //     }
    //     $dataArr = array();
    //     foreach($montharray as $key => $val)
    //     {
    //         $dataArr[] = array(
    //             "Count" => $val,
    //             "key" => $key
    //         );
    //     }
    //     return json_encode($dataArr);
    // }
}
