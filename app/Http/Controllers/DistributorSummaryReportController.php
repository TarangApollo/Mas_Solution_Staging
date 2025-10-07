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
use App\Models\Distributor;
use Illuminate\Support\Facades\Auth;

class DistributorSummaryReportController extends Controller
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

            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $distributorMaster = Distributor::orderBy('Name', 'asc')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => Session::get('CompanyId')])->get();

            $ticketList = TicketMaster::select(
                'companydistributor.iDistributorId',
                DB::raw('companydistributor.Name as Name'),
                DB::raw('count(ticketmaster.iTicketId) as count')
            )
                ->where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
                ->when($request->iDistributorId, fn($query, $iDistributorId) => $query->where('ticketmaster.iDistributorId', $iDistributorId))
                ->when($request->iCompanyId, fn($query, $iCompanyId) => $query->where('ticketmaster.iCompanyId', $iCompanyId))
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->join('companydistributor', 'companydistributor.iDistributorId', '=', 'ticketmaster.iDistributorId')
                ->groupBy('iDistributorId')
                ->orderBy('Name', 'asc')
                ->get();

            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            // dd($CompanyMaster);


            $postarray = array(
                "iDistributorId" => $request->iDistributorId,
                "iCompanyId" => $request->iCompanyId,
                "daterange" => $request->daterange
            );
            return view('wladmin.distributorsummary.index', compact('distributorMaster', 'ticketList', 'CompanyMaster',   'postarray'));
        } else {
            return redirect()->route('home');
        }
    }

    //public function info($id,$iComnentId,$iSubComponentId)
    public function info(Request $request)
    {
        // dd($request);
        if (Auth::User()->role_id == 2) {
            $id = $request->iDistributorId;
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }

            $GetDistributorName = DB::table('companydistributor')
                ->where(['isDelete' => 0, "iStatus" => 1, "iDistributorId" => $id])
                ->orderBy('Name', 'ASC')
                ->first();

            $ticketList = TicketMaster::select(
                'ticketmaster.iTicketId',
                'ticketmaster.strTicketUniqueID',
                'ticketmaster.iCityId',
                'ticketmaster.iCompanyId',
                'ticketmaster.iDistributorId',
                DB::raw("(select companyclient.CompanyName from companyclient where companyclient.iCompanyClientId=ticketmaster.iCompanyId) as DistributorName")
            )
                ->where(['isDelete' => 0, 'iStatus' => 1])
                ->where('ticketmaster.iDistributorId', '=', $id)
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->get();
            // dd($ticketList);

            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1, "iCityId" => $id])
                ->orderBy('strCityName', 'ASC')
                ->first();
            //dd($ticketList);
            foreach ($ticketList as $sub) {
                $res[$sub['DistributorName']]['DistributorName'] = $sub['DistributorName'];
                $res[$sub['DistributorName']]['iTicketId'][] = str_pad($sub['iTicketId'], 4, "0", STR_PAD_LEFT);
                $res[$sub['DistributorName']]['strTicketUniqueID'][] = $sub['strTicketUniqueID'] ?? str_pad($sub['iTicketId'], 4, "0", STR_PAD_LEFT);
            }

            foreach ($res as &$sub) {
                $sub['iTicketId'] = implode(",", $sub['iTicketId']);
                $sub['strTicketUniqueID'] = implode(",", $sub['strTicketUniqueID']);
            }
            $res = array_values($res);

            return view('wladmin.distributorsummary.info', compact('GetDistributorName', 'ticketList', 'citymasters', 'res'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callinfo($id)
    {
        // dd($id);
        if (Auth::User()->role_id == 2) {
            $ticketInfo = TicketMaster::select(
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
                ->first();
            // dd($ticketInfo);

            $ticketDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => 0])->get();
            $tickethistory[] = array("Status" => $ticketInfo->ticketName, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);

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
                $tickethistory[] = array("Status" => $log->ticketName, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            }
            return view('wladmin.distributorsummary.callinfo', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
        } else {
            return redirect()->route('home');
        }
    }
}
