<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\CompanyClient;
use App\Models\TicketMaster;
use App\Models\CallAttendent;
use App\Models\System;
use App\Models\TicketDetail;
use App\Models\TicketLog;
use App\Models\SubComponent;
use App\Models\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportType;
use Illuminate\Support\Facades\Route;

class CallListController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            
            $users = DB::table('users')->where(['id' => auth()->user()->id])->first();
            
            if (isset($users->rows_visible) && $users->rows_visible > 0) {
                $perpage = $users->rows_visible;
            } else {
                $perpage = 50;
            }
            
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            }
            if($request->iTicketYearId){
                $yeardetail = DB::table('yearlog')->where('iYearId', $request->iTicketYearId)->first();
                $formDate = date('Y-m-d', strtotime($yeardetail->startDate));
                $toDate = date('Y-m-d', strtotime($yeardetail->endDate));
            }
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            $CompanyClients = CompanyClient::select('companyclient.*')
                ->orderBy('CompanyName', 'ASC')
                ->where(['companyclient.isDelete' => 0, 'companyclient.iCompanyId' => $CompanyMaster->iCompanyId])
                ->get();
            $ticketListquery = TicketMaster::select(
                'ticketmaster.*',
                'finalStatus',
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
                "users.first_name",
                "users.last_name",
                DB::raw("(select callDuration from ticketcall where ticketcall.iTicketId=ticketmaster.iTicketId order by iTicketCallId desc limit 1) as callDuration"),
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
                DB::raw("(select strSupportType from supporttype where supporttype.iSuppotTypeId=ticketmaster.iSupportType order by iSuppotTypeId desc limit 1) as strSupportType")
            )
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", 'left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                // ->join('callattendent as l2', "l2.iCallAttendentId", "=", "ticketmaster.iLevel2CallAttendentId", " left outer")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                //->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')

                ->when($request->searchText, fn ($query, $searchText) => $query->where('ticketmaster.iTicketId', $searchText))
                ->when($request->CompanyClientId, fn ($query, $CompanyClientId) => $query->where('ticketmaster.iCompanyId', $CompanyClientId))
                //->when($request->exeId, fn ($query, $exeId) => $query->where('ticketmaster.iCallAttendentId', '=', $exeId))
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('ticketmaster.iSystemId', $iSystemId))
                ->when($request->LevelId, fn ($query, $LevelId) => $query->where('ticketmaster.LevelId', $LevelId))
                ->when($request->iComponentId, fn ($query, $iComponentId) => $query->where('ticketmaster.iComnentId', $iComponentId))
                //->when($request->iSubComponentId, fn ($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', $iSubComponentId))
                //->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->when($request->daterange, fn ($query, $daterange) => $query->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate))
                ->when($request->iTicketMonth, fn ($query, $iTicketMonth) => $query->whereMonth('ticketmaster.strEntryDate', $iTicketMonth))
                
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->when($request->iSupportType, fn ($query, $iSupportType) => $query->where('ticketmaster.iSupportType', $iSupportType))
                ->orderBy('iTicketId', 'DESC');
            if (isset($request->level))
                if ($request->level != null)
                    if ($request->level != '0' && $request->level != 6 && $request->level != 7) {
                        $ticketListquery->where('ticketmaster.finalStatus', $request->level);
                    } else if ($request->level == 6) {
                        $ticketListquery->where('ComplainDate', '>=', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'));
                    } else if ($request->level == 7) {
                        $ticketListquery->where('ComplainDate', '<=', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'));
                    } else {
                        $ticketListquery->whereIn('ticketmaster.finalStatus', array(0, 3));
                    }

            //$ticketList  = $ticketListquery->paginate($perpage);
            // dd($ticketListquery->toSql(), $ticketListquery->getBindings());
            $ticketList  = $ticketListquery->get();
            // $ticketList  = $ticketListquery->toSql();
            // echo $request->CompanyClientId;
            // echo $formDate."<br />";
            // echo $toDate."<br />";
            // echo Session::get('CompanyId') . "<br />";
            // dd($ticketList);

            $postarray = array();
            foreach ($request->request as $key => $value) {
                $postarray[$key] = $value;
            }
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $CompanyMaster->iCompanyId])->distinct()->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strComponent', 'ASC')->get();
            $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBY('strSupportType', 'ASC')->get();

            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1])
                ->whereIn('iUserId', function ($query) {
                    $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
                })
                ->get();


            $postarray = array(
                "searchText" => $request->searchText,
                "CompanyClientId" => $request->CompanyClientId,
                "exeId" => $request->exeId,
                "iSystemId" => $request->iSystemId,
                "iComponentId" => $request->iComponentId,
                "iSubComponentId" => $request->iSubComponentId,
                "daterange" => $request->daterange,
                "level" => $request->level,
                "LevelId" => $request->LevelId,
                "iSupportType" => $request->iSupportType
            );
            return view('wladmin.callList.index', compact('CompanyClients', 'supporttypes', 'executiveList', 'ticketList', 'systems', 'componentLists', 'subcomponents', 'postarray'));
        } else {
            return redirect()->route('home');
        }
    }

    public function info($id)
    {
        if (Auth::User()->role_id == 2) {
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
                // ->join('callattendent as l2', "l2.iCallAttendentId", "=", "ticketmaster.iLevel2CallAttendentId", " left outer")
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

            $ticketDetail = TicketDetail::where(['iStatus' => 1, 'isDelete' => 0, "iTicketId" => $id, "iTicketLogId" => 0, 'isAdditional' => 0])->get();
            // dd($ticketDetail);
            if ($ticketInfo->closelevel != '')
                $ticketInfo->LevelId = $ticketInfo->closelevel ?? $ticketInfo->LevelId;
            else
                $ticketInfo->LevelId = $ticketInfo->openLevel ?? $ticketInfo->LevelId;
            $tickethistory[] = array("Status" => 'Complaint Register', "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->oldStatusDatetime, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            $tickethistory[] = array("Status" => $ticketInfo->startStatus, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);

            $ticketLogs = TicketLog::select('ticketlog.*', 't1.ticketName as ticketstatus', 't3.ticketName as oldstatus', 'callattendent.iExecutiveLevel', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'callattendent.strFirstName', 'callattendent.strLastName', 'users.first_name', 'users.last_name')
                ->join('ticketstatus as t1', "t1.istatusId", "=", "ticketlog.iStatus", 'left outer')
                ->join('ticketstatus as t3', "t3.istatusId", "=", "ticketlog.oldStatus", ' left outer')
                ->join('callattendent', "callattendent.iCallAttendentId", "=", "ticketlog.iCallAttendentId", ' left outer')
                ->join('users', "users.id", "=", "ticketlog.iEntryBy")
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketlog.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketlog.iIssueTypeId", ' left outer')
                ->where("iticketId", "=", $id)->get();
            // dd($ticketLogs);

            foreach ($ticketLogs as $log) {
                $ticketlogDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => $log->iTicketLogId])->get();
                $log['gallery'] = $ticketlogDetail;
                if ($log->oldStatus != $log->iStatus){
                    if($log->oldStatus != 3){
                        $tickethistory[] = array("Status" => $log->oldstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->oldStatusDatetime, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => "");
                    }
                }
                $tickethistory[] = array("Status" => $log->ticketstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => "");
            }


            $additionalData = TicketDetail::select(
                'ticketdetail.*',
                'users.first_name',
                'users.last_name',
            )
                ->where(['iStatus' => 1, 'isDelete' => 0, "iTicketId" => $id, "iTicketLogId" => 0, 'isAdditional' => 1])
                ->join('users', 'users.id', '=', 'ticketdetail.iEnterBy')
                ->get();
            // dd($additionalData);

            $additionalDataCount =  $additionalData->count();

            $additionalRecording = DB::table('ticketcall')
                ->select('ticketcall.*', 'users.first_name', 'users.last_name')
                ->join('users', "users.id", "=", "ticketcall.iEnterBy")
                // ->join('ticketcall', "ticketcall.iTicketId", "=", "ticketlog.iticketId", 'left outer')
                ->where("ticketcall.iTicketId", "=", $id)
                ->get();
            $additionalRecordingCount = $additionalRecording->count();
            // dd($additionalRecordingCount);

            return view('wladmin.callList.info', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory", "additionalData", 'additionalDataCount', 'additionalRecording', 'additionalRecordingCount'));
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        // dd($Id);
        if (Auth::User()->role_id == 2 || Auth::User()->role_id == 3) {
            $ticketDetail = TicketDetail::where(['iStatus' => 1, 'isDelete' => 0, "iTicketDetailId" => $Id])->first();
            // dd($ticketDetail);
            if ($ticketDetail->DocumentType == 2) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationpath = $root . '/ticket_images/';
                unlink($destinationpath . $ticketDetail->DocumentName);
            } else {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationpath = $root . '/ticket_video/';
                unlink($destinationpath . $ticketDetail->DocumentName);
            }

            DB::table('ticketdetail')->where(['iStatus' => 1, 'isDelete' => 0, 'iTicketDetailId' => $Id])->delete();

            return back()->with('Success', 'Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }


    public function reorder_column(Request $request)
    {
        // dd($request);
        $userid = Auth::user()->id;
        // Retrieve the current URL
        $referer = $request->server('HTTP_REFERER');
        // Match the route using the URL
        $route = Route::getRoutes()->match(app('request')->create($referer));
        // Retrieve the route name
        $routeName = $route->getName();
        $checkboxesJson = json_encode($request->checkboxes);

        $menudata =  DB::table('reorder_columns')->where(['strUrl' => $routeName, 'iUserId' => $userid])->first();

        if (!$menudata) {
            $data = array(
                'strUrl' => $routeName,
                'json' => $checkboxesJson,
                'iUserId' => $userid,
            );
            DB::table('reorder_columns')->insert($data);
        } else {
            $data = array(
                'json' => $checkboxesJson
            );
            DB::table('reorder_columns')->where(['strUrl' => $routeName, 'iUserId' => $userid])->update($data);
        }

        return response()->json(['message' => 'Checkboxes saved successfully!']);
    }
}
