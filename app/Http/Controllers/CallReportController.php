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
use App\Models\Loginlog;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportType;
use Illuminate\Support\Facades\Route;

class CallReportController extends Controller
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
            $ticketListquery = TicketMaster::select(
                'ticketmaster.*',
                'finalStatus',
                'ticketmaster.iCallAttendentId',
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
                "users.first_name",
                "users.last_name",
                "l2.iExecutiveLevel as closelevel",
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                FROM subcomponent
                WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                GROUP BY iCompanyId) as strSubComponent'),
                DB::raw("(select callDuration from ticketcall where ticketcall.iTicketId=ticketmaster.iTicketId order by iTicketCallId desc limit 1) as callDuration"),
                DB::raw("(select call_state from ticketcall where ticketcall.iTicketId=ticketmaster.iTicketId order by iTicketCallId desc limit 1) as call_state"),
                DB::raw("(select iExecutiveLevel from callattendent where callattendent.iUserId=ticketmaster.iCallAttendentId) as openLevel"),
                DB::raw("(select strSupportType from supporttype where supporttype.iSuppotTypeId=ticketmaster.iSupportType order by iSuppotTypeId desc limit 1) as strSupportType"),
            )
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", 'left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('callattendent as l2', "l2.iCallAttendentId", "=", "ticketmaster.iLevel2CallAttendentId", " left outer")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                //->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->when($request->searchText, fn ($query, $searchText) => $query->where('ticketmaster.iTicketId', $searchText))
                //->when($request->CompanyClientId, fn ($query, $CompanyClientId) => $query->where('ticketmaster.iCompanyId', $CompanyClientId))
                ->when($request->exeId, fn ($query, $exeId) => $query->where('ticketmaster.iCallAttendentId', '=', $exeId))
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('ticketmaster.iSystemId', $iSystemId))
                ->when($request->LevelId, fn ($query, $LevelId) => $query->where('ticketmaster.LevelId', $LevelId))
                //->when($request->iComponentId, fn ($query, $iComponentId) => $query->where('ticketmaster.iComnentId', $iComponentId))
                //->when($request->iSubComponentId, fn ($query, $iSubComponentId) => $query->where('ticketmaster.iSubComponentId', $iSubComponentId))
                ->when($request->daterange, fn ($query, $daterange) => $query->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate))
                ->when($request->OEMCompany, fn ($query, $OEMCompany) => $query->where('ticketmaster.OemCompannyId', $OEMCompany))
                //->when($request->CallOutcome, fn ($query, $CallOutcome) => $query->select('call_state')->form('ticketcall')->where('call_state', $CallOutcome))
                ->when($request->iSupportType, fn ($query, $iSupportType) => $query->where('ticketmaster.iSupportType', $iSupportType))

                ->when($request->CallOutcome, fn ($query, $CallOutcome) => $query->WhereIn(
                    'ticketmaster.iTicketId',
                    function ($query) use ($CallOutcome) {
                        $query->select('ticketcall.iTicketId')
                            ->from('ticketcall')
                            ->where('call_state', $CallOutcome);
                    }
                ))
                // ->groupBy('ticketmaster.CustomerMobile')
                ->orderBy('iTicketId', 'DESC');
            if ($request->yearId != "") {
                $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
                $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                $ticketListquery->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('ticketmaster.strEntryDate', [$datefrom, $dateto]));
                //$ticketListquery->where('ticketmaster.strEntryDate', '<=', $dateto);
            } 
            if (isset($request->TicketStatus))
                if ($request->TicketStatus != null)
                    if ($request->TicketStatus != '0' && $request->TicketStatus != 6 && $request->TicketStatus != 7) {
                        $ticketListquery->where('ticketmaster.finalStatus', $request->TicketStatus);
                    } else if ($request->TicketStatus == 6) {
                        //$ticketListquery->where('ticketmaster.finalStatus', '0');
                        $ticketListquery->where('ComplainDate', '>=', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'));
                    } else if ($request->TicketStatus == 7) {
                        //$ticketListquery->where('ticketmaster.finalStatus', '0');
                        $ticketListquery->where('ComplainDate', '<=', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'));
                    } else {
                        $ticketListquery->where('ticketmaster.finalStatus', '0');
                    }

            // $ticketList  = $ticketListquery->toSql();
            // echo $ticketList;
            // dd($ticketList);
            $ticketList  = $ticketListquery->get();

            $postarray = array();
            foreach ($request->request as $key => $value) {
                $postarray[$key] = $value;
            }
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1])->distinct()->orderBy('strSystem', 'ASC')->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1])->orderBy('strComponent', 'ASC')->get();

            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1])
                ->whereIn('iUserId', function ($query) {
                    $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
                })
                ->get();
            $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1])->orderBY('strSupportType', 'ASC')->get();

            $postarray = array(
                "searchText" => $request->searchText,
                "OEMCompany" => $request->OEMCompany,
                "iSystemId" => $request->iSystemId,
                "TicketStatus" => $request->TicketStatus,
                "daterange" => $request->daterange,
                "exeId" => $request->exeId,
                "LevelId" => $request->LevelId,
                "CallOutcome" => $request->CallOutcome,
                "iComponentId" => $request->iComponentId,
                "iSubComponentId" => $request->iSubComponentId,
                "iSupportType" => $request->iSupportType
            );
            return view('admin.call_report.index', compact('CompanyMaster', 'executiveList', 'ticketList', 'systems', 'componentLists', 'subcomponents', 'postarray', 'supporttypes'));
        } else {
            return redirect()->route('home');
        }
    }

    public function getsystem(Request $request)
    {
        $SystemLists = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->OEMCompany])
            ->orderBy('strSystem', 'ASC')
            ->get();
        $html = "";
        if (count($SystemLists) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($SystemLists as $system) {
                $html .= '<option value="' . $system->iSystemId . '">' . $system->strSystem . '</option>';
            }
        } else {
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    function getCallAttendant(Request $request)
    {
        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1, 'iOEMCompany' => $request->OEMCompany])
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
            })
            ->get();
        $html = "";
        if (count($executiveList) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($executiveList as $executive) {
                $html .= '<option value="' . $executive->iUserId . '">' . $executive->strFirstName . " " . $executive->strLastName . '</option>';
            }
        } else {
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    function getSupportType(Request $request)
    {
        $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->OEMCompany])->orderBY('strSupportType', 'ASC')->get();

        $html = "";
        if (count($supporttypes) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($supporttypes as $supporttype) {
                $html .= '<option value="' . $supporttype->iSuppotTypeId . '">' . $supporttype->strSupportType . '</option>';
            }
        } else {
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    public function infoindex(Request $request, $id)
    {
        if (Auth::User()->role_id == 1) {
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
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                FROM subcomponent
                WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                GROUP BY iCompanyId) as strSubComponent'),
                DB::raw("(select iExecutiveLevel from callattendent where callattendent.iUserId=ticketmaster.iCallAttendentId) as openLevel"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iTicketEditedBy) as TicketEditedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iCallAttendentId) as TicketCreatedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id in (select  callattendent.iUserId from callattendent where callattendent.iCallAttendentId=ticketmaster.iLevel2CallAttendentId)) as TicketAssignTo")
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
            if ($ticketInfo->closelevel != '')
                $ticketInfo->LevelId = $ticketInfo->closelevel;
            else
                $ticketInfo->LevelId = $ticketInfo->openLevel;
            $tickethistory[] = array("Status" => 'Complaint Register', "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->oldStatusDatetime, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            $tickethistory[] = array("Status" => $ticketInfo->finalstatus, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);

            $ticketLogs = TicketLog::select('ticketlog.*',  't1.ticketName as ticketstatus', 't3.ticketName as oldstatus', 'callattendent.iExecutiveLevel', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'callattendent.strFirstName', 'callattendent.strLastName', 'users.first_name', 'users.last_name')
                ->join('ticketstatus as t1', "t1.istatusId", "=", "ticketlog.iStatus", 'left outer')
                ->join('ticketstatus as t3', "t3.istatusId", "=", "ticketlog.oldStatus", ' left outer')
                ->join('callattendent', "callattendent.iCallAttendentId", "=", "ticketlog.iCallAttendentId", ' left outer')
                ->join('users', "users.id", "=", "ticketlog.iEntryBy")
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketlog.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketlog.iIssueTypeId", ' left outer')
                ->where("iticketId", "=", $id)->get();

            foreach ($ticketLogs as $log) {
                $ticketlogDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => $log->iTicketLogId])->get();
                $log['gallery'] = $ticketlogDetail;
                if ($log->oldStatus != $log->iStatus)
                    $tickethistory[] = array("Status" => $log->oldstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->oldStatusDatetime, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
                $tickethistory[] = array("Status" => $log->ticketstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            }
            return view('admin.call_report.info', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
        } else {
            return redirect()->route('home');
        }
    }

    public function attendanceindex(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $date_range = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            } else {
                $formDate = date('Y-m-01 00:00:00');
                $toDate  = date('Y-m-t 12:59:59'); // A leap year!
            }
            //'iOEMCompany' =>
            $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1])->get();

            return view('admin.attendance.index', compact('callAttendent', 'formDate', 'toDate', 'date_range'));
        } else {
            return redirect()->route('home');
        }
    }

    public function attendanceinfoindex(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, 'iCallAttendentId' => $request->iCallAttendentId])->first();
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            } else {
                $formDate = date('Y-m-01 00:00:00');
                $toDate  = date('Y-m-t 23:59:59'); // A leap year!
            }
            //select(DB::raw("DISTINCT(DATE_format(strEntryDate,'%Y-%m-%d')) as 'strEntryDate'"),'userId','action')
            if ($request->daterange != "") {
                $Loginlogs = Loginlog::where('strEntryDate', '>=', $formDate)->where('strEntryDate', '<=', $toDate)
                    ->where(["userId" => $callAttendent->iUserId])
                    ->orderBy('id', 'asc')
                    ->get();
            } else {
                if (isset($date_range) && $date_range != "") {
                    $to_Date = $toDate;
                } else {
                    $to_Date = date("Y-m-d");
                }
                //$callAttendent->iUserId;
                $Loginlogs = Loginlog::where(DB::raw("DATE_format(strEntryDate,'%Y-%m-%d')"), '=', DB::raw("DATE_format('" . $to_Date . "','%Y-%m-%d')"))
                    ->where(["userId" => $callAttendent->iUserId])
                    ->orderBy('id', 'asc')
                    ->get();
                //dd($Loginlogs);
            }

            return view('admin.attendance.info', compact('callAttendent', 'Loginlogs'));
        } else {
            return redirect()->route('home');
        }
    }

    public function attendancedownload(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            } else {
                $formDate = date('Y-m-01 00:00:00');
                $toDate  = date('Y-m-t 12:59:59'); // A leap year!
            }
            $date_range = $request->daterange;
            $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1])->get();
            return view('admin.attendance.download', compact('callAttendent', 'formDate', 'toDate', 'date_range'));
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
            // dd($data);
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
