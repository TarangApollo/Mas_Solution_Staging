<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\DB;
use App\Models\Faq;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyClient;
use App\Models\TicketMaster;
use App\Models\CallAttendent;
use App\Models\System;
use App\Models\TicketDetail;
use App\Models\TicketLog;
use App\Models\SubComponent;
use App\Models\Component;
use Auth;


class AdminCustomerListController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::User()->role_id == 1) {
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            }

            $pastMonthDate = "";
            if (isset($request->isDashboardList)) {
                $fMonth = date('m', strtotime($formDate));
                $pastMonthDate = date('Y-' . $fMonth . '-01');
            }

            $ticketListquery = TicketMaster::select(
                DB::raw('DISTINCT ticketmaster.CustomerMobile'),
                'CustomerName as strCustomerName',
                DB::raw('count(iTicketId) as issueCount'),
                DB::raw('(select callcompetency.strCallCompetency from callcompetency where callcompetency.iCallCompetency= ticketmaster.CallerCompetencyId) as CallerCompetencyId'),
                DB::raw("(select count(*) from ticketdetail where ticketdetail.iTicketId=ticketmaster.iTicketId) as 'CallCount'"),
                DB::raw("(select strOEMCompanyName from companymaster where companymaster.iCompanyId=ticketmaster.OemCompannyId) as 'strOEMCompanyName'"),
                'companyclient.CompanyName',
                'ticketmaster.OemCompannyId'
            )
                ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId', 'left outer')
                ->where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
                ->when($request->iCompanyClientId, fn($query, $iCompanyClientId) => $query->where('ticketmaster.iCompanyId', $iCompanyClientId))
                ->when($request->searchText, fn($query, $searchText) => $query->where('CustomerName', 'like', '%' . $searchText . '%'))
                ->when($request->daterange, fn($query, $daterange) => $query->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate))
                ->when($request->OEMCompany, fn($query, $OEMCompany) => $query->where('ticketmaster.OemCompannyId', $OEMCompany))
                ->groupBy('ticketmaster.CustomerMobile', 'ticketmaster.OemCompannyId')
                ->orderBy('iTicketId', 'asc');
            //->where('ticketmaster.OemCompannyId','=',Session::get('CompanyId'))
            // if (isset($request->isDashboardList)) {
            //     $ticketListquery->whereNotIn(
            //         'ticketmaster.CustomerMobile',
            //         function ($query) use ($pastMonthDate) {
            //             $query->select('CustomerMobile')
            //                 ->from(with(new TicketMaster)->getTable())
            //                 ->where('strEntryDate', '<', $pastMonthDate);
            //         }
            //     );
            // }
            $ticketList  = $ticketListquery->get();
            // $ticketList  = $ticketListquery->toSql();
            // dd($ticketList);

            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $companyClients = CompanyClient::select('iCompanyClientId', 'CompanyName')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('CompanyName', 'ASC')
                ->get();

            $search_Company = $request->iCompanyClientId;
            $searchCustomer = $request->searchText;
            $search_daterange = $request->daterange;
            $OEMCompany = $request->OEMCompany;
            return view('admin.customer_list_report.index', compact('ticketList', 'companyClients', 'search_Company', 'searchCustomer', 'search_daterange', 'CompanyMaster', 'OEMCompany'));
        } else {
            return redirect()->route('home');
        }
    }

    public function info(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $OemCompannyId = $request->OemCompannyId;
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
                ->where('ticketmaster.OemCompannyId', '=', $OemCompannyId)
                ->orderBy('iTicketId', 'ASC')
                ->get();

            $ticketData = TicketMaster::select(DB::raw('DISTINCT ticketmaster.CustomerMobile'), 'CustomerName as strCustomerName')
                ->where(['isDelete' => 0, "iStatus" => 1, "ticketmaster.CustomerMobile" => $id])
                ->orderBy('iTicketId', 'ASC')
                ->first();
            return view('admin.customer_list_report.info', compact('ticketList', 'ticketData'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callinfo($id)
    {
        if (Auth::User()->role_id == 1) {
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
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                FROM subcomponent 
                WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                GROUP BY iCompanyId) as strSubComponent'),
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
            return view('admin.customer_list_report.callinfo', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
        } else {
            return redirect()->route('home');
        }
    }
}
