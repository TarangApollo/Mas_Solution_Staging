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



class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $daterange = $request->daterange;
        $formDate = "";
        $toDate = "";
        if ($request->daterange != "") {
            $daterange = explode("-", $request->daterange);
            $formDate = date('Y-m-d', strtotime($daterange[0]));
            $toDate = date('Y-m-d', strtotime($daterange[1]));
        }
        $ticketListquery = TicketMaster::select('ticketmaster.*', 'finalStatus', 'iCallAttendentId', 'ticketName', 'iTicketStatus', 'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName')
            ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", 'left outer')
            ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
            ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
            ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
            ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
            ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
            ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
            ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
            ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
            ->when($request->searchText, fn ($query, $searchText) => $query->where('ticketmaster.CustomerMobile', $searchText)->orwhere('ticketmaster.iTicketId',$searchText))
            ->when($request->OemCompannyId, fn ($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId))
            ->when($request->exeId, fn ($query, $exeId) => $query->where('ticketmaster.iCallAttendentId', '=', $exeId))
            ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('ticketmaster.iSystemId', $iSystemId))
            ->when($request->iComponentId, fn ($query, $iComponentId) => $query->where('ticketmaster.iComnentId', $iComponentId))
            ->when($request->iSubComponentId, fn ($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', $iSubComponentId))
            ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
            ->orderBy('iTicketId', 'DESC');
        if (isset($request->level))
            if ($request->level != null)
                if ($request->level != '0') {
                    $ticketListquery->where('ticketmaster.finalStatus', $request->level);
                } else {
                    $ticketListquery->where('ticketmaster.finalStatus', '0');
                }

        $ticketList  = $ticketListquery->get();

        //             ->toSql();
        //dd($ticketList);
        $postarray = array();
        foreach ($request->request as $key => $value) {
            $postarray[$key] = $value;
        }
        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
            ->orderBy('strOEMCompanyName', 'ASC')
            ->get();
        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1])
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
            })
            ->get();
        return view('call_attendant.complaints.index', compact('ticketList', 'CompanyMaster', 'executiveList', 'postarray'));
    }

    public function info($id)
    {
        $ticketInfo = TicketMaster::select('ticketmaster.*', 'iTicketId', 'ticketName', 'iTicketStatus', 'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName', 'icompanyclientprofile.strCompanyClientProfile', 'companydistributor.Name', 'statemaster.strStateName', 'citymaster.strCityName', 'supporttype.strSupportType', 'callcompetency.strCallCompetency')
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
        $tickethistory[] = array("Status" => $ticketInfo->ticketName, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name);

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
            $tickethistory[] = array("Status" => $log->ticketName, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name);
        }

        return view('call_attendant.complaints.info', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
    }

    public function editview($id)
    {
        $ticketInfo = TicketMaster::select('ticketmaster.*', 'iTicketId', 'ticketName', 'iTicketStatus', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'users.first_name', 'users.last_name', 'companyclient.CompanyName', 'icompanyclientprofile.strCompanyClientProfile', 'companydistributor.Name', 'statemaster.strStateName', 'citymaster.strCityName', 'supporttype.strSupportType', 'callcompetency.strCallCompetency')
            ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.iTicketStatus", 'left outer')
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
        }
        $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $ticketInfo->OemCompannyId])->get();
        $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $ticketInfo->OemCompannyId])->get();
        return view('call_attendant.complaints.edit', compact("ticketInfo", "ticketDetail", "ticketLogs", "issuetypes", "resolutionCategories"));
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
        try {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $ticketInfo = TicketMaster::where("iTicketId", "=", $request->iticketId)->first();

            if ($request->iStatus == 1) {
                DB::table('ticketmaster')->where("iTicketId", '=', $request->iticketId)->update(["ResolutionDate" => date('Y-m-d H:i:s')]);
            }
            $ticket = array(
                "iticketId" => $request->iticketId,
                "customerNumber" => $ticketInfo->CustomerMobile,
                "iResolutionCategoryId" => $request->iResolutionCategoryId ? $request->iResolutionCategoryId : 0,
                "iIssueTypeId" => $request->iIssueTypeId ? $request->iIssueTypeId : 0,
                "iStatus" => $request->iStatus,
                "LevelId" => $request->LevelId,
                "iEntryBy" => $session,
                "iCallAttendentId" => $request->iLevel2CallAttendentId ? $request->iLevel2CallAttendentId : 0,
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
                $chkCall = DB::table('ticketcall')
                    ->where(['callerNumber' => $ticketInfo->CustomerMobile, "isMaped" => 0, "call_state" => 'answered'])
                    ->first();
                if ($chkCall) {
                    $client = new \GuzzleHttp\Client();

                    $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/call-details?linkedid=' . $chkCall->callId, [
                        'headers' => [
                            'Authorization' => config('site_vars.tata_token'),
                            'accept' => 'application/json'
                        ],
                    ]);

                    $responseAPI = json_decode($response->getBody());
                    $updateData = array(
                        "tataCallId" => $chkCall->callId,
                        "recordUrl" => $responseAPI->recording_url
                    );

                    DB::table('ticketlog')->where("iTicketLogId", '=', $iTicketLogId)
                        ->where("tataCallId", '=', null)
                        ->update($updateData);
                    DB::table('ticketcall')->where("iTicketCallId", '=', $chkCall->iTicketCallId)
                        ->where(['callerNumber' => $ticketInfo->CustomerMobile, "isMaped" => 0, "call_state" => 'answered'])
                        ->update(["isMaped" => 1, "iTicketLogId" => $iTicketLogId]);
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
        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1])
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
            })
            ->get();
        return view('call_attendant.complaints.index', compact('ticketList', 'CompanyMaster', 'executiveList'))->with('Success', 'ticket Edited Successfully.');
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
            $unmapedCall = DB::table('ticketcall')->where(['callerNumber' => $callerNumber, 'isMaped' => '0', "call_state" => 'answered'])
                ->where('noOfAttempt', '<', '3')
                ->take(10)
                ->get();

            if (count($unmapedCall) > 0) {
                foreach ($unmapedCall as $call) {
                    $chkcomplain = DB::table('ticketmaster')->where('CustomerMobile', $callerNumber)
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
