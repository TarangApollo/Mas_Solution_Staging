<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketMaster;
use App\Models\TicketLog;
use App\Models\TicketDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyClient;
use Auth;
use Carbon\Carbon;

class CustomerSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $daterange = $request->daterange;
            $form_Date = "";
            $to_Date = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $form_Date = date('Y-m-d', strtotime($daterange[0]));
                $to_Date = date('Y-m-d', strtotime($daterange[1]));
            }

            // $pastMonthDate = "";
            // if (isset($request->isDashboardList)) {
            //     $fMonth = date('m', strtotime($formDate));
            //     $pastMonthDate = date('Y-' . $fMonth . '-01');
            // }
            $ticketListquery = TicketMaster::select(
                DB::raw('DISTINCT ticketmaster.CustomerMobile'),
                'CustomerName as strCustomerName',
                DB::raw('count(iTicketId) as issueCount'),
                DB::raw('(select callcompetency.strCallCompetency from callcompetency where callcompetency.iCallCompetency= ticketmaster.CallerCompetencyId) as CallerCompetencyId'),
                DB::raw("(select count(*) from ticketdetail where ticketdetail.iTicketId=ticketmaster.iTicketId) as 'CallCount'"),
                'companyclient.CompanyName'
            )
                ->leftjoin('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
                ->where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
                ->when($request->search_Company, fn($query, $search_Company) => $query->where('ticketmaster.iCompanyId', $search_Company))
                ->when($request->searchCustomer, fn($query, $searchCustomer) => $query->where('CustomerName', 'like', '%' . $searchCustomer . '%'));
                if($request->status == "Total"){
                    $ticketListquery->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$form_Date, $to_Date]));
                }
                $ticketListquery->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->groupBy('ticketmaster.CustomerMobile')
                ->orderBy('iTicketId', 'desc');
            if (isset($request->status)) {
                //if (isset($request->status) && $request->status != "Total") {
                
                    $endOfMonth = "";
                    if (isset($request->iCustomerYearID) && $request->iCustomerYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCustomerYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    //$yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    $formDate = date('Y-m-d  00:00:00', strtotime($yeardetail->startDate));
                    $toDate = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                    
                    if ($request->status == "TotalTD") {
                        $endOfMonth = "";
                        $year = 0;
                        $fMonth = 0;
                        if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                            $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                        } else {
                            $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                        }
                        $datefrom = $yeardetail->startDate;
                        if(isset($request->daterange)){
                                $year = 0;
                                $fMonth = date('m',strtotime($to_Date));
                                if($fMonth >=4 && $fMonth <= 12){
                                    $year = date('Y',strtotime($yeardetail->startDate));
                                } else {
                                    $year = date('Y',strtotime($yeardetail->endDate));
                                }
                                
                                $endDate = Carbon::create($year, $fMonth, 1);
                                $end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                                $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                                $datefrom = date('Y-m-d 00:00:00',strtotime($form_Date));
                                $ticketListquery->whereBetween('ticketmaster.strEntryDate', [$datefrom, $endOfMonth]);
                                if($fMonth > 0){
                                    $ticketListquery->whereMonth('ticketmaster.strEntryDate', $fMonth);
                                }
                                if($year > 0){
                                    $ticketListquery->whereYear('ticketmaster.strEntryDate', $year);
                                }
                            } else {
                                if ($request->New_Customer_Month != "") {
                                    if($request->New_Customer_Month >=4 && $request->New_Customer_Month <= 12){
                                        $year = date('Y',strtotime($yeardetail->startDate));
                                    } else {
                                        $year = date('Y',strtotime($yeardetail->endDate));
                                    }
                                    $fMonth = $request->New_Customer_Month;
                                    $endDate = Carbon::create($year, $fMonth, 1);
                                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                                }
                                else {
                                    $endOfMonth = $yeardetail->endDate;
                                }
                                
                                $ticketListquery->whereBetween('ticketmaster.strEntryDate', [$datefrom, $endOfMonth]);
                                if($fMonth > 0){
                                    $ticketListquery->whereMonth('ticketmaster.strEntryDate', $fMonth);
                                }
                                if($year > 0){
                                    $ticketListquery->whereYear('ticketmaster.strEntryDate', $year);
                                }
                            }
                        
                    } else if ($request->status == "TotalAll") {
                        /*if ($formDate == "" || $toDate == "") {
                            // $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                            // $formDate = date('Y-m-d  00:00:00', strtotime($yeardetail->startDate));
                            // $toDate = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                            $ticketListquery->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate);
                        }*/
                        if ($request->New_Customer_Month != "") {
                            $year = 0;
                            if($request->New_Customer_Month >=4 && $request->New_Customer_Month <= 12){
                                $year = date('Y',strtotime($yeardetail->startDate));
                            } else {
                                $year = date('Y',strtotime($yeardetail->endDate));
                            }
                            $month = $request->New_Customer_Month;
                            $endDate = Carbon::create($year, $month, 1);
                            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                            $ticketListquery->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                        } 
                        $dateto = $toDate;
                        if ($request->yearId != "") {
                            $ticketListquery->where('ticketmaster.strEntryDate', '<=', $dateto);
                        } 
                        else {
                            $ticketListquery->where('ticketmaster.strEntryDate', '<=', $dateto);
                        }
                    } else if($request->status == "new"){
                        /*$ticketListquery->whereMonth('ticketmaster.strEntryDate', date('m',strtotime($endOfMonth)))
                        ->whereYear('ticketmaster.strEntryDate',date('Y',strtotime($endOfMonth)))
                        ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('ticketmaster.strEntryDate', [$datefrom, $dateto]));*/
                        if (isset($request->iCustomerYearID) && $request->iCustomerYearID != "") {
                            $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCustomerYearID)->first();
                        } else {
                            $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                        }
                        
                        if ($request->New_Customer_Month != "") {
                            $year = 0;
                            if($request->New_Customer_Month >=4 && $request->New_Customer_Month <= 12){
                                $year = date('Y',strtotime($yeardetail->startDate));
                            } else {
                                $year = date('Y',strtotime($yeardetail->endDate));
                            }
                            $fMonth = $request->New_Customer_Month;
                            $endDate = Carbon::create($year, $fMonth, 1);
                            $end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                            $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                            $datefrom = $formDate;
                            $ticketListquery->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                                ->whereNotIn(
                                'ticketmaster.CustomerMobile',
                                function ($query) use ($end_Of_Month) {
                                    $query->select('ticketmaster.CustomerMobile')
                                        ->from(with(new TicketMaster)->getTable())
                                        ->where('strEntryDate', '<', $end_Of_Month)
                                        ->where('OemCompannyId', Session::get('CompanyId'));
                                }
                            );
                        } else {
                            if(isset($request->daterange)){
                                $year = 0;
                                $fMonth = date('m',strtotime($to_Date));
                                if($fMonth >=4 && $fMonth <= 12){
                                    $year = date('Y',strtotime($yeardetail->startDate));
                                } else {
                                    $year = date('Y',strtotime($yeardetail->endDate));
                                }
                                
                                $endDate = Carbon::create($year, $fMonth, 1);
                                $end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                                $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                                $datefrom = date('Y-m-d 00:00:00',strtotime($form_Date));
                                $ticketListquery->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                                    ->whereNotIn(
                                    'ticketmaster.CustomerMobile',
                                    function ($query) use ($end_Of_Month) {
                                        $query->select('ticketmaster.CustomerMobile')
                                            ->from(with(new TicketMaster)->getTable())
                                            ->where('strEntryDate', '<', $end_Of_Month)
                                            ->where('OemCompannyId', Session::get('CompanyId'));
                                    }
                                );
                            } else {
                                //dd($yeardetail);
                                $year = 0;
                                $fMonth = date('m');
                                if($fMonth >=4 && $fMonth <= 12){
                                    $year = date('Y',strtotime($yeardetail->startDate));
                                } else {
                                    $year = date('Y',strtotime($yeardetail->endDate));
                                }
                                $endDate = Carbon::create($year, $fMonth, 1);
                                
                                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                                
                                $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                                
                                //$ticketListquery->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                                $ticketListquery->whereNotIn(
                                    'ticketmaster.CustomerMobile',
                                    function ($query) use ($datefrom) {
                                        $query->select('ticketmaster.CustomerMobile')
                                            ->from(with(new TicketMaster)->getTable())
                                            ->where('strEntryDate', '<', $datefrom)
                                            ->where('OemCompannyId', Session::get('CompanyId'));
                                    }
                                );
                            }
                        }
                    } else {
                        $ticketListquery->whereIn(
                            'ticketmaster.CustomerMobile',
                            function ($query) use ($endOfMonth) {
                                $query->select('CustomerMobile')
                                    ->from(with(new TicketMaster)->getTable())
                                    //->where('strEntryDate', '<', $pastMonthDate)
                                    // ->where('strEntryDate', '<=', $endOfMonth)
                                    ->whereMonth('strEntryDate', date('m',strtotime($endOfMonth)))->whereYear('strEntryDate', date('Y',strtotime($endOfMonth)))
                                    ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'));
                            }
                        );
                    }
                    
                    // $pastMonthDate = "";
                    // $fMonth = "";
                    // if (isset($request->isDashboardList)) {
                    //     if ($request->New_Customer_Month == "" || $request->New_Customer_Month == null) {
                    //         $fMonth = date('m');
                    //     } else {
                    //         $fMonth = $request->New_Customer_Month;
                    //     }
                    // }
                    // if ($fMonth == "") {
                    //     $fMonth = date('m');
                    // }
                    // $pastMonthDate = date('Y-' . $fMonth . '-01');
                    // if (isset($request->isDashboardList)) {
                    /*$ticketListquery->whereNotIn(
                        'ticketmaster.CustomerMobile',
                        function ($query) use ($pastMonthDate) {
                            $query->select('CustomerMobile')
                                ->from(with(new TicketMaster)->getTable())
                                ->where('strEntryDate', '<', $pastMonthDate)
                                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'));
                        }
                    );*/
                    
                    // } else
                    //->when($request->search_state, fn ($query, $search_state) => $query->where('ticketmaster.iStateId', $search_state))
                    // ->whereMonth('ticketmaster.strEntryDate', $request->New_Customer_Month)

                    // if ($formDate == "" || $toDate == "") {
                    //     $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    //     $formDate = date('Y-m-d  00:00:00', strtotime($yeardetail->startDate));
                    //     $toDate = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                    //     $ticketListquery->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate);
                    // }
            }
            $ticketList  = $ticketListquery->get();
            // $ticketList  = $ticketListquery->toSql();
            // dd($ticketList);
            $companyClients = CompanyClient::select('iCompanyClientId', 'CompanyName')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('CompanyName', 'ASC')
                ->get();

            $search_Company = $request->search_Company;
            $searchCustomer = $request->searchCustomer;
            $search_daterange = $request->daterange;
            $status = $request->status;
            return view('wladmin.customersummary.index', compact('ticketList', 'companyClients', 'search_Company', 'searchCustomer', 'search_daterange', 'status'));
        } else {
            return redirect()->route('home');
        }
    }

    public function info(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $id = $request->CustomerMobile;
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $ticketList = TicketMaster::select('ticketmaster.iTicketId', 'ticketmaster.strTicketUniqueID', 'CustomerName as strCustomerName', DB::raw('(select callcompetency.strCallCompetency from callcompetency where callcompetency.iCallCompetency= ticketmaster.CallerCompetencyId) as CallerCompetencyId'))
                ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
                ->where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
                ->where('ticketmaster.CustomerMobile', '=', $id)
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->orderBy('iTicketId', 'ASC')
                ->get();

            $ticketData = TicketMaster::select(DB::raw('DISTINCT ticketmaster.CustomerMobile'), 'CustomerName as strCustomerName')
                ->where(['isDelete' => 0, "iStatus" => 1, "ticketmaster.CustomerMobile" => $id])
                ->orderBy('iTicketId', 'ASC')
                ->first();
            return view('wladmin.customersummary.info', compact('ticketList', 'ticketData'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callinfo($id)
    {
        if (Auth::User()->role_id == 2) {
            $ticketInfo = TicketMaster::
                //select('ticketmaster.*', 'iTicketId', 'ticketName', 'iTicketStatus', 'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName', 'icompanyclientprofile.strCompanyClientProfile', 'companydistributor.Name', 'statemaster.strStateName', 'citymaster.strCityName', 'supporttype.strSupportType', 'callcompetency.strCallCompetency')
                select(
                    'ticketmaster.*',
                    'iTicketId',
                    'ticketName',
                    'iTicketStatus',
                    'CustomerMobile',
                    'CustomerName',
                    'companymaster.strOEMCompanyName',
                    'system.strSystem',
                    'component.strComponent',
                    //'subcomponent.strSubComponent', 
                    DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                FROM subcomponent 
                WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                GROUP BY iCompanyId) as strSubComponent'),
                    'resolutioncategory.strResolutionCategory',
                    'issuetype.strIssueType',
                    'ticketmaster.ComplainDate',
                    'users.first_name',
                    'users.last_name',
                    'ticketmaster.ResolutionDate',
                    'companyclient.CompanyName',
                    'icompanyclientprofile.strCompanyClientProfile',
                    'companydistributor.Name',
                    'statemaster.strStateName',
                    'citymaster.strCityName',
                    'supporttype.strSupportType',
                    'callcompetency.strCallCompetency',
                    DB::raw("CASE
                        WHEN ticketmaster.iLevel2CallAttendentId != 0 THEN (
                            SELECT ca.iExecutiveLevel
                            FROM callattendent ca
                            WHERE ca.iCallAttendentId = ticketmaster.iLevel2CallAttendentId
                            LIMIT 1
                        )
                        WHEN (
                            SELECT COUNT(*)
                            FROM companyuser cu
                            WHERE cu.iUserId = ticketmaster.iCallAttendentId
                        ) != 0 THEN 2
                        ELSE NULL
                    END AS closelevel"),
                    DB::raw("(select iExecutiveLevel from callattendent where callattendent.iUserId=ticketmaster.iCallAttendentId) as openLevel"),
                    DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iTicketEditedBy) as TicketEditedBy"),
                    DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iCallAttendentId) as TicketCreatedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id in (select  callattendent.iUserId from callattendent where callattendent.iCallAttendentId=ticketmaster.iLevel2CallAttendentId)) as TicketAssignTo")
                )
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", ' left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('icompanyclientprofile', "icompanyclientprofile.iCompanyClientProfileId", "=", "ticketmaster.iCompanyProfileId", ' left outer')
                ->join('companydistributor', "companydistributor.iDistributorId", "=", "ticketmaster.iDistributorId", 'left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->join('statemaster', "statemaster.iStateId", "=", "ticketmaster.iStateId", ' left outer')
                ->join('citymaster', "citymaster.iCityId", "=", "ticketmaster.iCityId", ' left outer')
                ->join('supporttype', "supporttype.iSuppotTypeId", "=", "ticketmaster.iSupportType", ' left outer')
                ->join('callcompetency', "callcompetency.iCallCompetency", "=", "ticketmaster.CallerCompetencyId", ' left outer')
                ->where("iTicketId", "=", $id)
                ->first();
            
            $ticketDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => 0])->get();
            
            //$tickethistory[] = array("Status" => $ticketInfo->ticketName, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            if ($ticketInfo->closelevel != '')
                $ticketInfo->LevelId = $ticketInfo->closelevel ?? $ticketInfo->LevelId;
            else
                $ticketInfo->LevelId = $ticketInfo->openLevel ?? $ticketInfo->LevelId;
            $tickethistory[] = array("Status" => 'Complaint Register', "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->oldStatusDatetime, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            $tickethistory[] = array("Status" => $ticketInfo->startStatus, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);

            

            $ticketLogs = TicketLog::select('ticketlog.*', 'ticketName', 'callattendent.iExecutiveLevel', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'callattendent.strFirstName', 'callattendent.strLastName', 'users.first_name', 'users.last_name')
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketlog.iStatus", 'left outer')
                ->join('callattendent', "callattendent.iCallAttendentId", "=", "ticketlog.iCallAttendentId", ' left outer')
                ->join('users', "users.id", "=", "ticketlog.iEntryBy")
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketlog.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketlog.iIssueTypeId", ' left outer')
                ->where("iticketId", "=", $id)->get();

            foreach ($ticketLogs as $log) {
                $ticketlogDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => $log->iTicketLogId])->get();
                $log['gallery'] = $ticketlogDetail;
                if ($log->oldStatus != $log->iStatus){
                    if($log->oldStatus != 3){
                        $tickethistory[] = array("Status" => $log->oldstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->oldStatusDatetime, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => "");
                    }
                }
                $tickethistory[] = array("Status" => $log->ticketName, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            }
            return view('wladmin.customersummary.callinfo', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
        } else {
            return redirect()->route('home');
        }
    }
}
