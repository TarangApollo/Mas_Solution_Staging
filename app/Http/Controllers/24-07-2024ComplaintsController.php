<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketMaster;
use App\Models\TicketDetail;
use App\Models\TicketLog;
use App\Models\CompanyMaster;
use App\Models\Component;
use App\Models\SubComponent;
use App\Models\System;
use App\Models\IssueType;
use App\Models\ResolutionCategory;
use App\Models\CallAttendent;
use File;
use Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            }

            $ticketListquery = TicketMaster::select(
                'ticketmaster.*',
                'finalStatus',
                'iCallAttendentId',
                'ticketName',
                'iTicketStatus',
                'CustomerMobile',
                'CustomerName',
                'companymaster.strOEMCompanyName',
                'system.strSystem',
                'component.strComponent',
                //'subcomponent.strSubComponent',
                'resolutioncategory.strResolutionCategory',
                'issuetype.strIssueType',
                'ticketmaster.ComplainDate',
                'users.first_name',
                'users.last_name',
                'ticketmaster.ResolutionDate',
                'companyclient.CompanyName',
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                  FROM subcomponent
                  WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                  GROUP BY iCompanyId) as strSubComponent')
            )
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", 'left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->when(
                    $request->searchText,
                    fn ($query, $searchText) => $query->where('ticketmaster.CustomerMobile', $searchText)
                        ->orwhere('ticketmaster.iTicketId', 'like', ltrim($searchText, "0") . '%')
                        ->orwhere('companyclient.CompanyName', 'like', '%' . ltrim($searchText, "0") . '%')
                )
                ->when($request->OemCompannyId, fn ($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId))
                ->when($request->exeId, fn ($query, $exeId) => $query->where('ticketmaster.iCallAttendentId', '=', $exeId))
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('ticketmaster.iSystemId', $iSystemId))
                ->when($request->iComponentId, fn ($query, $iComponentId) => $query->where('ticketmaster.iComnentId', $iComponentId))
                //->when($request->iSubComponentId, fn ($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', $iSubComponentId))
                ->when($request->iSubComponentId, fn ($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', 'like', '%' . $iSubComponentId . '%'))
                //->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->when($request->daterange, fn ($query, $daterange) => $query->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate))
                ->orderBy('iTicketId', 'DESC');
            if (isset($request->level))
                if ($request->level != null)
                    if ($request->level != '0') {
                        $ticketListquery->where('ticketmaster.finalStatus', $request->level);
                    } else {
                        $ticketListquery->where('ticketmaster.finalStatus', '0');
                    }

            $ticketList  = $ticketListquery->get();

            //->toSql();
            //dd($ticketList);

            $postarray = array();
            foreach ($request->request as $key => $value) {
                $postarray[$key] = $value;
            }
            if (isset($request->OemCompannyId))
                $search_company = $request->OemCompannyId;
            else
                $search_company = 6;
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])
                ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iComponentId, fn ($query, $iComponentId) => $query->where('iComponentId', $iComponentId))
                ->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1])
                ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('strSystem', $iSystemId))
                ->get();

            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1])
                ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1])
                ->whereIn('iUserId', function ($query) {
                    $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
                })
                ->get();
            return view('call_attendant.complaints.index', compact('ticketList', 'CompanyMaster', 'executiveList', 'postarray', 'systemLists', 'componentLists', 'subcomponents'));
        } else {
            return redirect()->route('home');
        }
    }

    public function info($id)
    {
        // dd(Auth::User());
        if (Auth::User()->role_id == 3) {
            $ticketInfo = TicketMaster::select(
                'ticketmaster.*',
                'iTicketId',
                't2.ticketName as startStatus',
                't1.ticketName as finstatus',
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
                "l2.iExecutiveLevel as closelevel",
                DB::raw("(select iExecutiveLevel from callattendent where callattendent.iUserId=ticketmaster.iCallAttendentId) as openLevel"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iTicketEditedBy) as TicketEditedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iCallAttendentId) as TicketCreatedBy")

            )
                ->join('ticketstatus as t1', "t1.istatusId", "=", "ticketmaster.finalStatus", ' left outer')
                ->join('ticketstatus as t2', "t2.istatusId", "=", "ticketmaster.iTicketStatus", ' left outer')
                ->join('ticketstatus as t3', "t3.istatusId", "=", "ticketmaster.oldStatus", ' left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('callattendent as l2', "l2.iCallAttendentId", "=", "ticketmaster.iLevel2CallAttendentId", " left outer")
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

            $ticketDetail = TicketDetail::where(['iStatus' => 1, 'isDelete' => 0, "iTicketId" => $id, "iTicketLogId" => 0, 'isAdditional' => 0])->get();

            // dd($ticketInfo);
            if ($ticketInfo->LevelId == 2 && $ticketInfo->iLevel2CallAttendentId != 0)
                $ticketInfo->LevelId = $ticketInfo->closelevel;
            else
                $ticketInfo->LevelId = $ticketInfo->openLevel;
            $tickethistory[] = array("Status" => 'Complaint Register', "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->oldStatusDatetime, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name);
            $tickethistory[] = array("Status" => $ticketInfo->startStatus, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name);

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
                if ($log->oldStatus != $log->iStatus)
                    $tickethistory[] = array("Status" => $log->oldstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->oldStatusDatetime, "user" => $log->first_name . " " . $log->last_name);
                $tickethistory[] = array("Status" => $log->ticketstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name);
            }

            $additionalData = TicketDetail::select(
                'ticketdetail.*',
                'users.first_name',
                'users.last_name',
            )
                ->where(['iStatus' => 1, 'isDelete' => 0, "iTicketId" => $id, "iTicketLogId" => 0, 'isAdditional' => 1])
                ->join('users', 'users.id', '=', 'ticketdetail.iEnterBy')
                ->get();
            $additionalDataCount =  $additionalData->count();

            $additionalRecording = DB::table('ticketcall')
                ->select('ticketcall.*', 'users.first_name', 'users.last_name')
                ->join('users', "users.id", "=", "ticketcall.iEnterBy")
                // ->join('ticketcall', "ticketcall.iTicketId", "=", "ticketlog.iticketId", 'left outer')
                ->where("ticketcall.iTicketId", "=", $id)
                ->get();
            // dd($additionalRecording);

            return view('call_attendant.complaints.info', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory", "additionalData", 'additionalDataCount', 'additionalRecording'));
        } else {
            return redirect()->route('home');
        }
    }

    public function editview($id)
    {

        if (Auth::User()->role_id == 3) {
            $ticketInfo = TicketMaster::select('ticketmaster.*', 'iTicketId',  't2.ticketName as startStatus', 't1.ticketName as finstatus', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'users.first_name', 'users.last_name', 'companyclient.CompanyName', 'icompanyclientprofile.strCompanyClientProfile', 'companydistributor.Name', 'statemaster.strStateName', 'citymaster.strCityName', 'supporttype.strSupportType', 'callcompetency.strCallCompetency')
                ->join('ticketstatus as t1', "t1.istatusId", "=", "ticketmaster.finalStatus", ' left outer')
                ->join('ticketstatus as t2', "t2.istatusId", "=", "ticketmaster.iTicketStatus", ' left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", 'left outer')
                ->join('icompanyclientprofile', "icompanyclientprofile.iCompanyClientProfileId", "=", "ticketmaster.iCompanyProfileId", ' left outer')
                ->join('companydistributor', "companydistributor.iDistributorId", "=", "ticketmaster.iDistributorId", 'left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
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
                if ($log->LevelId == '2')
                    $ticketInfo->LevelId = $log->LevelId;
            }
            $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $ticketInfo->OemCompannyId])->get();
            $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $ticketInfo->OemCompannyId])->get();
            $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1, "iExecutiveLevel" => 2, "iOEMCompany" => $ticketInfo->OemCompannyId])
                ->whereIn('iUserId', function ($query) {
                    $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
                })
                ->get();
            return view('call_attendant.complaints.edit', compact("ticketInfo", "ticketDetail", "ticketLogs", "issuetypes", "resolutionCategories", "executiveList"));
        } else {
            return redirect()->route('home');
        }
    }

    public function getExecutives($id)
    {

        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1, "iExecutiveLevel" => 2, "iOEMCompany" => $id])
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
            })
            ->get();
        $html = '';
        if (count($executiveList) > 0) {
            $html = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($executiveList as $executive) {
                $html .= '<option value="' . $executive->iCallAttendentId . '">' . $executive->strFirstName . " " . $executive->strLastName . '</option>';
            }
        } else {
            $html = '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    public function update(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $ticketInfo = TicketMaster::where("iTicketId", "=", $request->iticketId)->first();

                if ($request->iStatus == 1 || $request->iStatus == 4 || $request->iStatus == 5) {
                    DB::table('ticketmaster')->where("iTicketId", '=', $request->iticketId)->update(["ResolutionDate" => date('Y-m-d H:i:s')]);
                }


                $levelId = 1;

                if ($request->LevelId) {
                    if (Session::get('exeLevel') == 2)
                        $levelId = 2;
                    else
                        $levelId = $request->LevelId;
                } else {
                    if (Session::get('exeLevel') == 2)
                        $levelId = 2;
                }
                $level2Exe = 0;
                if ($levelId) {
                    if ($levelId == '2') {
                        $level2Exe = $request->iLevel2CallAttendentId;
                    } else {
                        if (Session::get('exeLevel') == 2) {
                            $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                                ->first();
                            $level2Exe = $callAttendent->iCallAttendentId;
                        }
                    }
                }
                $ticket = array(
                    "iticketId" => $request->iticketId,
                    "customerNumber" => $ticketInfo->CustomerMobile,
                    "iResolutionCategoryId" => $request->iResolutionCategoryId ? $request->iResolutionCategoryId : 0,
                    "strIssue" => $request->strIssue ? $request->strIssue : "",
                    "iIssueTypeId" => $request->iIssueTypeId ? $request->iIssueTypeId : 0,
                    "iStatus" => $request->iStatus,
                    "oldStatus" => $request->oldStatus ? $request->oldStatus : $request->iStatus,
                    "oldStatusDatetime" => $request->oldStatusDatetime ? $request->oldStatusDatetime : date('Y-m-d H:i:s'),
                    "LevelId" =>   $levelId,
                    "iEntryBy" => $session,
                    "iCallAttendentId" => $level2Exe,
                    "newResolution" => $request->newResolution,
                    "comments" => $request->comments,
                    "strIP" => $request->ip()
                );

                $iTicketLogId =  DB::table('ticketlog')->insertGetId($ticket);

                DB::table('ticketmaster')->where("iTicketId", '=', $request->iticketId)->update(["finalStatus" => $request->iStatus]);
                $iStatus = 1;
                if ($iTicketLogId) {

                    if ($request->hasfile('tcktImages')) {

                        foreach ($request->file('tcktImages') as $file) {

                            $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                            $name = time() . rand(1, 50) . '.' . $file->extension();
                            $destinationpath = $root . "ticket_images/";
                            if (!file_exists($destinationpath)) {
                                mkdir($destinationpath, 0755, true);
                            }
                            if ($file->move($destinationpath, $name)) {
                                $Data = array(
                                    "iTicketId" =>  $request->iticketId,
                                    "iTicketLogId" => $iTicketLogId,
                                    "DocumentType" => 2,
                                    "DocumentName" => $name,
                                    "iStatus" => 1,
                                    "isDelete" => 0,
                                    "strEntryDate" => date('Y-m-d H:i:s'),
                                    "strIP" => $request->ip(),
                                );

                                $ticketdetailId = DB::table('ticketdetail')->insertGetId($Data);
                            } else {
                                $iStatus = 0;
                            }
                        }
                    }

                    if ($request->hasfile('tcktVideo')) {

                        foreach ($request->file('tcktVideo') as $file) {
                            $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                            $name = time() . rand(1, 50) . '.' . $file->extension();
                            $destinationpath = $root . "ticket_video/";
                            if (!file_exists($destinationpath)) {
                                mkdir($destinationpath, 0755, true);
                            }
                            if ($file->move($destinationpath, $name)) {

                                $Data = array(
                                    "iTicketId" =>  $request->iticketId,
                                    "iTicketLogId" => $iTicketLogId,
                                    "DocumentType" => 3,
                                    "DocumentName" => $name,
                                    "iStatus" => 1,
                                    "isDelete" => 0,
                                    "strEntryDate" => date('Y-m-d H:i:s'),
                                    "strIP" => $request->ip(),
                                );

                                $ticketdetailId = DB::table('ticketdetail')->insertGetId($Data);
                            } else {
                                $iStatus = 0;
                            }
                        }
                    }
                    $currentTime = time();
                    $beginning_of_day = strtotime("midnight", $currentTime);
                    $chkCall = DB::table('ticketcall')
                        ->where(['callerNumber' => $ticketInfo->CustomerMobile, "isMaped" => 0])
                        ->where('call_state', 'answered')
                        ->whereBetween('entryDatetime', [$beginning_of_day, $currentTime])
                        ->orderby('iTicketCallId', 'DESC')
                        ->first();
                    if ($chkCall) {
                        $client = new \GuzzleHttp\Client();

                        $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/call/records?call_id=' . $chkCall->callId, [
                            'headers' => [
                                'Authorization' => config('site_vars.tata_token'),
                                'accept' => 'application/json'
                            ],
                        ]);

                        $responseAPI = json_decode($response->getBody(), true);
                        $apiResult =  $responseAPI['results'][0];
                        if ($responseAPI['count'] == 1) {
                            if (isset($apiResult['status'])) {
                                if ($apiResult['status'] == 'answered' && $chkCall->callId == $apiResult['call_id']) {
                                    $updateData = array(
                                        "tataCallId" => $chkCall->callId,
                                        "recordUrl" => $apiResult['recording_url']
                                    );

                                    DB::table('ticketlog')->where("iTicketLogId", '=', $iTicketLogId)
                                        ->where("tataCallId", '=', null)
                                        ->update($updateData);
                                    DB::table('ticketcall')->where("iTicketCallId", '=', $chkCall->iTicketCallId)
                                        ->where(['callerNumber' => $ticketInfo->CustomerMobile, "isMaped" => 0, "call_state" => 'answered'])
                                        ->update(["isMaped" => 1, "iTicketLogId" => $iTicketLogId]);
                                }
                            }
                        }
                    }
                } else {
                    $iStatus = 0;
                }

                if ($iStatus == 1) {
                    Session::flash('Success', 'Ticket Edited Succefully!');
                } else {
                    Session::flash('Error', 'Error in update data!');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                Session::flash('Error', $th->getMessage());
            }

            $ticketList = TicketMaster::select('ticketmaster.*', 'ticketName',  'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate')
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", 'left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId")
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->orderBy('iTicketId', 'DESC')
                ->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1])
                ->get();
            $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1])
                ->whereIn('iUserId', function ($query) {
                    $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
                })
                ->get();
            return redirect()->route('complaint.index', compact('CompanyMaster'));
            return view('call_attendant.complaints.index', compact('ticketList', 'CompanyMaster', 'executiveList'))->with('Success', 'ticket Edited Successfully.');
        } else {
            return redirect()->route('home');
        }
    }
    public function livecall(Request $request)
    {
        // $client = new \GuzzleHttp\Client();

        // $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/live_calls', [
        //     'headers' => [
        //         'Authorization' => config('site_vars.tata_token'),
        //         'accept' => 'application/json',
        //     ],
        // ]);

        $responseAPI = json_decode($request, true);

        foreach ($responseAPI as $resArray) {

            $Data = array(
                "callJsonLog" => $resArray,
                "callId" => $resArray['call_id'],
                "agentId" => $resArray['agent_name'],
                "call_state" => $resArray['call_state'],
                'entryDatetime' => time(),
                "strIP" => $request->ip(),
            );

            echo $ticketdetailId = DB::table('ticketcall')->insertGetId($Data);
        }
    }
    public function callDetail(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/call/records?call_id=1680530532.187075', [
            'headers' => [
                'Authorization' => config('site_vars.tata_token'),
                'accept' => 'application/json'
            ],
        ]);

        $responseAPI = json_decode($response->getBody());
        dd($responseAPI);

        // $Data = array(
        //     "callJsonLog" => $responseAPI,
        //     'entryDatetime' => time(),
        //     "strIP" => $request->ip(),
        // );


        // echo $ticketdetailId = DB::table('ticketcall')->insertGetId($Data);

    }


    public function getCallList(Request $request)
    {

        $reqToken = $request->header('authorization');
        if ($reqToken == config('site_vars.tata_token')) {
            $responseAPI = json_decode($request->getContent());
            $callerNumber = $responseAPI->caller_id_number;
            $Data = array(
                "callJsonLog" => $request->getContent(),
                "callId" => $responseAPI->call_id,
                "agentNumber" => $responseAPI->answered_agent_number,
                "callDuration" => $responseAPI->duration,
                "call_state" => $responseAPI->call_status,
                "callerNumber" => $responseAPI->caller_id_number,
                'entryDatetime' => time(),
                "strIP" => $request->ip(),
            );

            $ticketdetailId = DB::table('ticketcall')->insertGetId($Data);
            if ($responseAPI->call_status == 'answered') {
                $currentTime = time();
                $beginning_of_day = strtotime("midnight", $currentTime);
                $unmapedCall = DB::table('ticketcall')
                    ->where(['callerNumber' => $callerNumber, 'isMaped' => '0', "call_state" => 'answered'])
                    ->whereBetween('entryDatetime', [$beginning_of_day, $currentTime])
                    ->where('noOfAttempt', '<', '3')
                    ->take(1)
                    ->orderby('iTicketCallId', 'DESC')
                    ->get();

                if (count($unmapedCall) > 0) {
                    foreach ($unmapedCall as $call) {
                        $currentTimetck = date('Y-m-d H:i:s');
                        $beginning_of_daytck = date('Y-m-d 00:00:00', strtotime("midnight", $currentTime));
                        $chkcomplain = DB::table('ticketmaster')->where('CustomerMobile', $callerNumber)
                            ->wherebetween('strEntryDate', [$beginning_of_daytck, $currentTimetck])
                            ->where('tataCallId', null)
                            ->where('recordUrl', null)
                            ->first();
                        if ($chkcomplain) {
                            $updateData = array(
                                "tataCallId" => $responseAPI->call_id,
                                "recordUrl" => $responseAPI->recording_url
                            );

                            DB::table('ticketmaster')->where("iTicketId", '=', $chkcomplain->iTicketId)
                                ->where("tataCallId", '=', null)
                                ->update($updateData);
                            DB::table('ticketcall')->where("iTicketCallId", '=', $call->iTicketCallId)
                                ->where(['callerNumber' => $callerNumber, "isMaped" => 0, "call_state" => 'answered'])
                                ->update(["isMaped" => 1, "iTicketId" => $chkcomplain->iTicketId]);
                        } else {
                            $chkcomplain = DB::table('ticketlog')->where('customerNumber', $callerNumber)
                                ->wherebetween('strEntryDate', [$beginning_of_daytck, $currentTimetck])
                                ->where('tataCallId', null)
                                ->where('recordUrl', null)
                                ->first();
                            if ($chkcomplain) {
                                $updateData = array(
                                    "tataCallId" => $responseAPI->call_id,
                                    "recordUrl" => $responseAPI->recording_url
                                );

                                DB::table('ticketlog')->where("iTicketLogId", '=', $chkcomplain->iTicketLogId)
                                    ->where("tataCallId", '=', null)
                                    ->update($updateData);
                                DB::table('ticketcall')->where("iTicketCallId", '=', $call->iTicketCallId)
                                    ->where(['callerNumber' => $callerNumber, "isMaped" => 0, "call_state" => 'answered'])
                                    ->update(["isMaped" => 1, "iTicketLogId" => $chkcomplain->iTicketLogId]);
                            } else {
                                DB::table('ticketcall')->where("iTicketCallId", '=', $call->iTicketCallId)
                                    ->where(['callerNumber' => $callerNumber, "isMaped" => 0, "call_state" => 'answered'])
                                    ->update(["noOfAttempt" => ($call->noOfAttempt + 1)]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getOemCompannyExecutives(Request $request)
    {
        $id = $request->id;
        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1, "iOEMCompany" => $id])
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
            })
            ->get();
        $html = '';
        if (count($executiveList) > 0) {
            $html = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($executiveList as $executive) {
                $html .= '<option value="' . $executive->iCallAttendentId . '">' . $executive->strFirstName . " " . $executive->strLastName . '</option>';
            }
        } else {
            $html = '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }


    public function additionalImagesVideosStore(Request $request)
    {
        // dd($request);
        try {
            $userid = Auth::User()->id;
            // dd($userid);
            if ($request->hasfile('additional_images')) {

                foreach ($request->file('additional_images') as $file) {
                    $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                    $name = time() . rand(1, 50) . '.' . $file->extension();
                    $destinationpath = $root . "ticket_images/";
                    if (!file_exists($destinationpath)) {
                        mkdir($destinationpath, 0755, true);
                    }
                    if ($file->move($destinationpath, $name)) {
                        $Data = array(
                            "iTicketId" =>  $request->iTicketId,
                            "DocumentType" => 2,
                            "isAdditional" => 1,
                            "iEnterBy" => $userid,
                            "DocumentName" => $name,
                            "iStatus" => 1,
                            "isDelete" => 0,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            "strIP" => $request->ip(),
                        );

                        $ticketdetailId = DB::table('ticketdetail')->insertGetId($Data);
                    } else {
                        $iStatus = 0;
                    }
                }
            }

            if ($request->hasfile('additional_videos')) {

                foreach ($request->file('additional_videos') as $file) {
                    $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                    $name = time() . rand(1, 50) . '.' . $file->extension();
                    $destinationpath = $root . "ticket_video/";
                    if (!file_exists($destinationpath)) {
                        mkdir($destinationpath, 0755, true);
                    }
                    if ($file->move($destinationpath, $name)) {

                        $Data = array(
                            "iTicketId" =>  $request->iTicketId,
                            "DocumentType" => 3,
                            "isAdditional" => 1,
                            "iEnterBy" => $userid,
                            "DocumentName" => $name,
                            "iStatus" => 1,
                            "isDelete" => 0,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            "strIP" => $request->ip(),
                        );

                        $ticketdetailId = DB::table('ticketdetail')->insertGetId($Data);
                    } else {
                        $iStatus = 0;
                    }
                }
            }

            return back()->with('Success', 'Additional Images & Videos Created Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('Error', $th->getMessage());
        }
    }

    public function additionalrecording(Request $request)
    {
        // dd($request);
        try {
            // dd($request);
            $userid = Auth::User()->id;
            $seperatedValue = explode(',', $request->recordingIds);
            // dd($seperatedValue);


            foreach ($seperatedValue as $data) {


                $client = new \GuzzleHttp\Client();

                $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/call/records?call_id=' . $data, [
                    'headers' => [
                        'Authorization' => config('site_vars.tata_token'),
                        'accept' => 'application/json'
                    ],
                ]);
                $responseAPI = json_decode($response->getBody(), true);
                //dd($responseAPI);


                if ($responseAPI['count'] == 1) {
                    $apiResult = $responseAPI['results'][0];
                    if (isset($apiResult['status'])) {
                        if ($apiResult['status'] == 'answered' && $data == $apiResult['call_id']) {
                            $updateData = array(
                                "recordUrl" => $apiResult['recording_url'],
                                "iTicketId" => $request->iTicketId,
                                "iEnterBy" => $userid,
                                'isAdditional' => 1
                            );
                            // dd($updateData);
                            DB::table('ticketcall')->where([
                                "isAdditional" => 0, "iEnterBy" => 0, "iTicketLogId" => 0, 'iTicketId' => 0, 'callId' => $data
                            ])->update($updateData);
                        }
                    }
                }
            }

            return back()->with('success', 'Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('Error', $th->getMessage());
        }
    }
    
    public function additionalrecordingdelete(Request $request, $id)
    {
        // dd($id);
        try {
             $updateData = array(
                        "iTicketId" => 0,
                        "iTicketLogId" => 0,
                        "recordUrl" => null,
                        'isAdditional' => 0,
                        'iEnterBy' => 0
                    );
            DB::table('ticketcall')->where([
                "iTicketCallId" => $id])
                ->update($updateData);

            return back()->with('success', 'Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('Error', $th->getMessage());
        }
    }
}
