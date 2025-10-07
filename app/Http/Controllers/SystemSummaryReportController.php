<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketMaster;
use App\Models\TicketLog;
use App\Models\TicketDetail;
use App\Models\System;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\SubComponent;
use App\Models\Component;
use Auth;

class SystemSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $ticketList = TicketMaster::select(
                'ticketmaster.iSystemId',
                'ticketmaster.iComnentId',
                'ticketmaster.iSubComponentId',
                DB::raw('count(*) as issue'),
                DB::raw("(select strSystem from `system` where `system`.iSystemId=ticketmaster.iSystemId) as strSystem"),
                DB::raw("(select strComponent from component where component.iComponentId=ticketmaster.iComnentId) as strComponent"),
                //DB::raw("(select strSubComponent from subcomponent where subcomponent.iSubComponentId=ticketmaster.iSubComponentId) as strSubComponent")
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                    FROM subcomponent 
                    WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                    GROUP BY iCompanyId) as strSubComponent')
            )
                ->where(['isDelete' => 0, 'iStatus' => 1])
                ->when($request->iSystemId, fn($query, $iSystemId) => $query->where('ticketmaster.iSystemId', $iSystemId))
                ->when($request->iComponentId, fn($query, $iComponentId) => $query->where('ticketmaster.iComnentId', $iComponentId))
                ->when($request->iSubComponentId, fn($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', $iSubComponentId))
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->groupBy(['ticketmaster.iSystemId', 'ticketmaster.iComnentId', 'ticketmaster.iSubComponentId'])
                ->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $CompanyMaster->iCompanyId])->distinct()->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])->get();

            $postarray = array(
                "iSystemId" => $request->iSystemId,
                "iComponentId" => $request->iComponentId,
                "iSubComponentId" => $request->iSubComponentId,
                "daterange" => $request->daterange
            );
            return view('wladmin.systemSummary.index', compact('ticketList', 'CompanyMaster', 'systems', 'subcomponents', 'componentLists', 'postarray'));
        } else {
            return redirect()->route('home');
        }
    }

    //public function info($id,$iComnentId,$iSubComponentId)
    public function info(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $id = $request->iSystemId;
            $iComnentId = $request->iComnentId;
            $iSubComponentId = $request->iSubComponentId;
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $ticketListquery = TicketMaster::select(
                'ticketmaster.iTicketId',
                'ticketmaster.strTicketUniqueID',
                'ticketmaster.iSystemId',
                'ticketmaster.iComnentId',
                'ticketmaster.iSubComponentId',
                DB::raw("(select strSystem from `system` where `system`.iSystemId=ticketmaster.iSystemId) as strSystem"),
                DB::raw("(select strComponent from component where component.iComponentId=ticketmaster.iComnentId) as strComponent"),
                //DB::raw("(select strSubComponent from subcomponent where subcomponent.iSubComponentId=ticketmaster.iSubComponentId) as strSubComponent")
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                    FROM subcomponent 
                    WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                    GROUP BY iCompanyId) as strSubComponent')
            )
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where(['isDelete' => 0, 'iStatus' => 1])
                ->where('ticketmaster.iSystemId', '=', $id)
                ->where('ticketmaster.iComnentId', $iComnentId)
                ->where('ticketmaster.iSubComponentId', $iSubComponentId);
            $ticketList = $ticketListquery->get();
            $strSystem = "";
            $strComponent = "";
            $strSubComponent = "";
            foreach ($ticketList as $ticket) {
                if (isset($ticket->strSystem) && $ticket->strSystem != 0) {
                    $strSystem = $ticket->strSystem;
                }
                if (isset($ticket->strComponent) && $ticket->strComponent != 0) {
                    $strComponent = $ticket->strComponent;
                }
                if (isset($ticket->strSubComponent) && $ticket->strSubComponent != 0) {
                    $strSubComponent = $ticket->strSubComponent;
                }
            }
            return view('wladmin.systemSummary.info', compact('ticketList', 'strSystem', 'strComponent', 'strSubComponent'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callinfo($id)
    {
        if (Auth::User()->role_id == 2) {
            /*$ticketInfo = TicketMaster::select(
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
                //->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->join('statemaster', "statemaster.iStateId", "=", "ticketmaster.iStateId", ' left outer')
                ->join('citymaster', "citymaster.iCityId", "=", "ticketmaster.iCityId", ' left outer')
                ->join('supporttype', "supporttype.iSuppotTypeId", "=", "ticketmaster.iSupportType", ' left outer')
                ->join('callcompetency', "callcompetency.iCallCompetency", "=", "ticketmaster.CallerCompetencyId", ' left outer')
                ->where("iTicketId", "=", $id)
                ->first();*/
                
            $ticketInfo = TicketMaster::select(
                'ticketmaster.*',
                'iTicketId',
                't2.ticketName as startStatus',
                't1.ticketName as finalstatus',
                't3.ticketName as oldstatus',
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
                //"l2.iExecutiveLevel as closelevel",
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
                ->join('ticketstatus as t1', "t1.istatusId", "=", "ticketmaster.finalStatus", ' left outer')
                ->join('ticketstatus as t2', "t2.istatusId", "=", "ticketmaster.iTicketStatus", ' left outer')
                ->join('ticketstatus as t3', "t3.istatusId", "=", "ticketmaster.oldStatus", ' left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                //->join('callattendent as l2', "l2.iCallAttendentId", "=", "ticketmaster.iLevel2CallAttendentId", " left outer")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('icompanyclientprofile', "icompanyclientprofile.iCompanyClientProfileId", "=", "ticketmaster.iCompanyProfileId", ' left outer')
                ->join('companydistributor', "companydistributor.iDistributorId", "=", "ticketmaster.iDistributorId", 'left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                //->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->join('statemaster', "statemaster.iStateId", "=", "ticketmaster.iStateId", ' left outer')
                ->join('citymaster', "citymaster.iCityId", "=", "ticketmaster.iCityId", ' left outer')
                ->join('supporttype', "supporttype.iSuppotTypeId", "=", "ticketmaster.iSupportType", ' left outer')
                ->join('callcompetency', "callcompetency.iCallCompetency", "=", "ticketmaster.CallerCompetencyId", ' left outer')
                ->where("iTicketId", "=", $id)
                ->first();

            $ticketDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => 0])->get();
            //$tickethistory[] = array("Status" => $ticketInfo->ticketName ?? "", "Level" => "Level " . $ticketInfo->LevelId ?? "", "Date" => $ticketInfo->ComplainDate ?? "", "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
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
            return view('wladmin.systemSummary.callinfo', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
        } else {
            return redirect()->route('home');
        }
    }
}
