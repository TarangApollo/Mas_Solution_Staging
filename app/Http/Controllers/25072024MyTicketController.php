<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallAttendent;
use App\Models\TicketMaster;
use App\Models\TicketLog;
use App\Models\TicketDetail;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Component;
use App\Models\SubComponent;
use App\Models\System;
use App\Models\WlUser;
use Auth;

class MyTicketController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $userID = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                ->first();
            if (!$userID) {
                $userID = WlUser::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                    ->first();
            }

            $ticketListquery = TicketMaster::select('ticketmaster.*', 'finalStatus', 'ticketName', 'iTicketStatus', 'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName')
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", 'left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->join('ticketlog', "ticketlog.iticketId", "=", "ticketmaster.iTicketId", ' left outer')
                ->when($request->searchText, fn ($query, $searchText) => $query->where('ticketmaster.CustomerMobile', $searchText))
                ->when($request->OemCompannyId, fn ($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId))
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('ticketmaster.iSystemId', $iSystemId))
                ->when($request->iComponentId, fn ($query, $iComponentId) => $query->where('ticketmaster.iComnentId', $iComponentId))
                ->when($request->iSubComponentId, fn ($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', $iSubComponentId))
                ->where('ticketmaster.iCallAttendentId', $session)
                ->orWhere('ticketlog.iCallAttendentId', $userID->iCallAttendentId)
                ->orderBy('ticketmaster.iTicketId', 'DESC');
            if (isset($request->level))
                if ($request->level != null)
                    if ($request->level != '0') {
                        $ticketListquery->where('ticketmaster.finalStatus', $request->level);
                    } else {
                        $ticketListquery->where('ticketmaster.finalStatus', '0');
                    }

            $ticketList  = $ticketListquery->get();


            //   dd($ticketList);
            $postarray = array();
            foreach ($request->request as $key => $value) {
                $postarray[$key] = $value;
            }
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            if (isset($request->OemCompannyId))
                $search_company = $request->OemCompannyId;
            else
                $search_company = 6;
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])
                ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iComponentId, fn ($query, $search_component) => $query->where('iComponentId', $search_component))
                ->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1])
                ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iSystemId, fn ($query, $search_system) => $query->where('strSystem', $search_system))
                ->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1])
                ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->get();

            return view('call_attendant.myTicket.index', compact('ticketList', 'CompanyMaster',  'postarray', 'subcomponents', 'componentLists', 'systemLists'));
        } else {
            return redirect()->route('home');
        }
    }
}
