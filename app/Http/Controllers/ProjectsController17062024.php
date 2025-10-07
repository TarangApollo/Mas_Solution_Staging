<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyClientProfile;
use App\Models\CompanyClient;
use Illuminate\Support\Facades\DB;
use App\Models\SubComponent;
use App\Models\Component;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\CompanyMaster;
use App\Models\FaqDocument;
use App\Models\System;
use App\Models\infoTable;
use App\Models\ProjectProfile;
use App\Models\ProjectProfileFiles;
use App\Models\TicketMaster;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $search_project = $request->search_project;
            $search_state = $request->search_state;
            $search_city = $request->search_city;
            $daterange = $request->daterange;

            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            // First part of the union query
            $firstQuery = DB::table('project_profile')
                ->select([
                    'project_profile.iTicketId as iTicketId',
                    'project_profile.projectProfileId',
                    'project_profile.projectName',
                    'project_profile.iStateId',
                    'project_profile.iCityId',
                    'project_profile.strVertical',
                    'project_profile.strSubVertical',
                    'project_profile.strSI',
                    'project_profile.strEngineer',
                    'project_profile.strCommissionedIn',
                    'project_profile.iSystemId',
                    'project_profile.strPanel',
                    'project_profile.strPanelQuantity',
                    'project_profile.strDevices',
                    'project_profile.strDeviceQuantity',
                    'project_profile.strOtherComponents',
                    'project_profile.strBOQ',
                    'project_profile.strAMC',
                    'project_profile.strOtherInformation',
                    'project_profile.created_at',
                    'citymaster.strCityName',
                    'statemaster.strStateName',
                    'system.strSystem'
                ])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'project_profile.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'project_profile.iStateId')
                ->leftjoin('system', 'system.iSystemId', '=', 'project_profile.iSystemId')
                ->when($request->search_project, fn ($query, $search_project) => $query->where('project_profile.projectName', $search_project))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('project_profile.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('project_profile.iCityId', $search_city))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('project_profile.created_at', [$formDate, $toDate]))
                ->groupBy('project_profile.projectName');

            // Second part of the union query
            $secondQuery = DB::table('ticketmaster')
                ->select([
                    'ticketmaster.iTicketId as iTicketId',
                    DB::raw('"" as projectProfileId'),
                    'ticketmaster.ProjectName as projectName',
                    'ticketmaster.iStateId',
                    'ticketmaster.iCityId',
                    DB::raw('"" as strVertical'),
                    DB::raw('"" as strSubVertical'),
                    DB::raw('"" as strSI'),
                    DB::raw('"" as strEngineer'),
                    DB::raw('"" as strCommissionedIn'),
                    DB::raw('"" as iSystemId'),
                    DB::raw('"" as strPanel'),
                    DB::raw('"" as strPanelQuantity'),
                    DB::raw('"" as strDevices'),
                    DB::raw('"" as strDeviceQuantity'),
                    DB::raw('"" as strOtherComponents'),
                    DB::raw('"" as strBOQ'),
                    DB::raw('"" as strAMC'),
                    DB::raw('"" as strOtherInformation'),
                    'ticketmaster.strEntryDate as created_at', // Ensure consistent naming for date field
                    'citymaster.strCityName',
                    'statemaster.strStateName',
                    'system.strSystem'
                ])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'ticketmaster.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'ticketmaster.iStateId')
                ->leftjoin('system', 'system.iSystemId', '=', 'ticketmaster.iSystemId')
                ->when($request->search_project, fn ($query, $search_project) => $query->where('ticketmaster.projectName', $search_project))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('ticketmaster.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('ticketmaster.iCityId', $search_city))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.ProjectName', '!=', '')
                ->groupBy('ticketmaster.ProjectName');

            // Combining both queries with union all
            $unionQuery = $firstQuery->unionAll($secondQuery);

            // Wrapping the union query with a subquery to apply the outer group by
            $Projects = DB::table(DB::raw("({$unionQuery->toSql()}) as x"))
                ->mergeBindings($unionQuery)  // you need to pass the bindings of the union query
                ->orderBy('x.projectProfileId', 'desc')
                ->groupBy('x.projectName')
                ->get();
            // dd($Projects);

            // Debugging step: output the final SQL query and bindings




            $user = User::where(["id" => $session])->first();

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();

            $ticketmasters = DB::table('ticketmaster')
                ->select('ProjectName')
                ->where('isDelete', 0)
                ->where('iStatus', 1)
                ->where('ProjectName', '!=', '')
                ->orderBy('ProjectName', 'ASC')
                ->distinct()
                ->get();

            return view('wladmin.projects.index', compact('ticketmasters', 'search_project', 'user',  'statemasters', 'citymasters',  'search_state', 'search_city',  'Projects'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            //$Faq = Faq::orderBy('iFAQId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1, "system.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strSystem', 'ASC')
                ->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strComponent', 'ASC')
                ->get();
            return view('wladmin.Faq.add', compact('subcomponents', 'componentLists', 'CompanyMaster', 'systemLists'));
        } else {
            return redirect()->route('home');
        }
    }

    public function getsubcomponent(Request $request)
    {
        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

        $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iComponentId" => $request->iComponentId])
            ->orderBy('strSubComponent', 'ASC')
            ->get();

        $html = "";
        if (count($subcomponents) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($subcomponents as $subcomponent) {
                $html .= '<option value="' . $subcomponent->iSubComponentId . '">' . $subcomponent->strSubComponent . '</option>';
            }
        } else {
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    public function store(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $iFAQId = 0;
            $faqArr = array(
                "iCompanyId" => $request->iCompanyId,
                "iComponentId" => $request->iComponentId ? $request->iComponentId : 0,
                "iSubComponentId" => $request->iSubComponentId ? $request->iSubComponentId : 0,
                "iSystemId" => $request->iSystemId,
                "strFAQTitle" => $request->strFAQTitle,
                "strFAQDescription" => $request->strFAQDescription,
                "strEntryDate" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            $FAQId = Faq::create($faqArr);
            $iFAQId = $FAQId->id;

            if ($request->hasFile('strDocument')) {
                $img = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Document/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strDocument') as $docfile) {

                    $image = $docfile;
                    $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $img);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 1,
                        "strFileName" => $img,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }


            if ($request->hasFile('strImages')) {
                $imgName = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Image/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strImages') as $imagefile) {
                    $image = $imagefile;
                    $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $imgName);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 2,
                        "strFileName" => $imgName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }
            if ($request->hasFile('strVideo')) {
                $strVideoName = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Video/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strVideo') as $imagefile) {

                    $Video = $imagefile;
                    $strVideoName = pathinfo($Video->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $Video->getClientOriginalExtension();

                    $Video->move($destinationPath, $strVideoName);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 3,
                        "strFileName" => $strVideoName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }
            $userdata = User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "faqs",
                'tableAutoId'    => $iFAQId,
                'tableMainField'  => "Company Faqs",
                'action'     => "Inserted",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);
            return redirect()->route('faq.index')->with('Success', 'Faq Created Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function editview(Request $request, $id)
    {
        // dd($id);
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            $systemmasters = DB::table('system')
                ->where(['isDelete' => 0, "iStatus" => 1, "iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strSystem', 'ASC')
                ->get();

            $ticketmasters = DB::table('ticketmaster')
                ->select('ProjectName')
                ->where(['isDelete' => 0, 'iStatus' => 1, 'iTicketId' => $id])
                ->where('ProjectName', '!=', '')
                ->first();

            // $datas = ProjectProfile::where(['isDelete' => 0, "iStatus" => 1, 'iTicketId' => $id])->first();

            $firstQuery = DB::table('project_profile')
                ->select([
                    'project_profile.iTicketId as iTicketId',
                    'project_profile.projectProfileId',
                    'project_profile.projectName',
                    'project_profile.iStateId',
                    'project_profile.iCityId',
                    'project_profile.strVertical',
                    'project_profile.strSubVertical',
                    'project_profile.strSI',
                    'project_profile.strEngineer',
                    'project_profile.strCommissionedIn',
                    'project_profile.iSystemId',
                    'project_profile.strPanel',
                    'project_profile.strPanelQuantity',
                    'project_profile.strDevices',
                    'project_profile.strDeviceQuantity',
                    'project_profile.strOtherComponents',
                    'project_profile.strBOQ',
                    'project_profile.strAMC',
                    'project_profile.strOtherInformation',
                    'citymaster.strCityName',
                    'statemaster.strStateName',
                    'system.strSystem'
                ])
                ->where(['project_profile.iTicketId' => $id])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'project_profile.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'project_profile.iStateId')
                ->leftjoin('system', 'system.iSystemId', '=', 'project_profile.iSystemId')
                ->groupBy('project_profile.projectName');

            // Second part of the union query
            $secondQuery = DB::table('ticketmaster')
                ->select([
                    'ticketmaster.iTicketId as iTicketId',
                    DB::raw('"" as projectProfileId'),
                    'ticketmaster.ProjectName as projectName',
                    'ticketmaster.iStateId',
                    'ticketmaster.iCityId',
                    DB::raw('"" as strVertical'),
                    DB::raw('"" as strSubVertical'),
                    DB::raw('"" as strSI'),
                    DB::raw('"" as strEngineer'),
                    DB::raw('"" as strCommissionedIn'),
                    // DB::raw('"" as iSystemId'),
                    'ticketmaster.iSystemId',

                    DB::raw('"" as strPanel'),
                    DB::raw('"" as strPanelQuantity'),
                    DB::raw('"" as strDevices'),
                    DB::raw('"" as strDeviceQuantity'),
                    DB::raw('"" as strOtherComponents'),
                    DB::raw('"" as strBOQ'),
                    DB::raw('"" as strAMC'),
                    DB::raw('"" as strOtherInformation'),
                    'citymaster.strCityName',
                    'statemaster.strStateName',
                    'system.strSystem'
                ])
                ->where(['ticketmaster.iTicketId' => $id])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'ticketmaster.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'ticketmaster.iStateId')
                ->leftjoin('system', 'system.iSystemId', '=', 'ticketmaster.iSystemId')
                ->where('ticketmaster.ProjectName', '!=', '')
                ->groupBy('ticketmaster.ProjectName');

            // Combining both queries with union all
            $unionQuery = $firstQuery->unionAll($secondQuery);

            // Wrapping the union query with a subquery to apply the outer group by
            $datas = DB::table(DB::raw("({$unionQuery->toSql()}) as x"))
                ->mergeBindings($unionQuery)  // you need to pass the bindings of the union query
                ->groupBy('x.projectName')
                ->first();
            // dd($datas);

            if ($datas) {
                $Documents = ProjectProfileFiles::where(["projectProfileId" => $datas->projectProfileId])->get();
            } else {
                $Documents = 0;
            }


            return view('wladmin.projects.edit', compact('statemasters', 'citymasters', 'id', 'systemmasters', 'ticketmasters', 'datas', 'Documents'));
        } else {
            return redirect()->route('home');
        }
    }
    public function infoview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {

            $firstQuery = DB::table('project_profile')
                ->select([
                    'project_profile.iTicketId as iTicketId',
                    'project_profile.projectProfileId',
                    'project_profile.projectName',
                    'project_profile.iStateId',
                    'project_profile.iCityId',
                    'project_profile.strVertical',
                    'project_profile.strSubVertical',
                    'project_profile.strSI',
                    'project_profile.strEngineer',
                    'project_profile.strCommissionedIn',
                    'project_profile.iSystemId',
                    'project_profile.strPanel',
                    'project_profile.strPanelQuantity',
                    'project_profile.strDevices',
                    'project_profile.strDeviceQuantity',
                    'project_profile.strOtherComponents',
                    'project_profile.strBOQ',
                    'project_profile.strAMC',
                    'project_profile.strOtherInformation',
                    'citymaster.strCityName',
                    'statemaster.strStateName',
                    'system.strSystem'
                ])
                ->where(['project_profile.iTicketId' => $id])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'project_profile.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'project_profile.iStateId')
                ->leftjoin('system', 'system.iSystemId', '=', 'project_profile.iSystemId')
                ->groupBy('project_profile.projectName');

            // Second part of the union query
            $secondQuery = DB::table('ticketmaster')
                ->select([
                    'ticketmaster.iTicketId as iTicketId',
                    DB::raw('"" as projectProfileId'),
                    'ticketmaster.ProjectName as projectName',
                    'ticketmaster.iStateId',
                    'ticketmaster.iCityId',
                    DB::raw('"" as strVertical'),
                    DB::raw('"" as strSubVertical'),
                    DB::raw('"" as strSI'),
                    DB::raw('"" as strEngineer'),
                    DB::raw('"" as strCommissionedIn'),
                    DB::raw('"" as iSystemId'),
                    DB::raw('"" as strPanel'),
                    DB::raw('"" as strPanelQuantity'),
                    DB::raw('"" as strDevices'),
                    DB::raw('"" as strDeviceQuantity'),
                    DB::raw('"" as strOtherComponents'),
                    DB::raw('"" as strBOQ'),
                    DB::raw('"" as strAMC'),
                    DB::raw('"" as strOtherInformation'),
                    'citymaster.strCityName',
                    'statemaster.strStateName',
                    'system.strSystem'
                ])
                ->where(['ticketmaster.iTicketId' => $id])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'ticketmaster.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'ticketmaster.iStateId')
                ->leftjoin('system', 'system.iSystemId', '=', 'ticketmaster.iSystemId')
                ->where('ticketmaster.ProjectName', '!=', '')
                ->groupBy('ticketmaster.ProjectName');

            // Combining both queries with union all
            $unionQuery = $firstQuery->unionAll($secondQuery);

            // Wrapping the union query with a subquery to apply the outer group by
            $Projects = DB::table(DB::raw("({$unionQuery->toSql()}) as x"))
                ->mergeBindings($unionQuery)  // you need to pass the bindings of the union query
                ->groupBy('x.projectName')
                ->first();


            // dd($Projects->iTicketId);
            $Documents = ProjectProfileFiles::select(
                'project_profile_files.projectProfileFilesId',
                'project_profile_files.iTicketId',
                'project_profile_files.projectProfileId',
                'project_profile_files.strBOQUpload',
                'project_profile_files.CompletionDocumentUpload',
                'project_profile_files.iUserId',
                'project_profile_files.iUserId',
                'project_profile_files.created_at',
                'users.id',
                'users.first_name',
                'users.last_name',
            )
                ->where(["project_profile_files.projectProfileId" => $Projects->projectProfileId])
                ->leftjoin('users', 'project_profile_files.iUserId', '=', 'users.id')
                ->get();
            // dd($Documents);
            $infoTables = infoTable::where(["tableName" => "faqs", "tableAutoId" => $id])->orderBy('id', 'Desc')->limit(10)->get();

            return view('wladmin.projects.info', compact('Projects', 'Documents', 'infoTables', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($id);
        // dd($request);
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            $data = array(
                "iTicketId" => $id,
                "projectName" => $request->projectName,
                "iStateId" => $request->iStateId ?? 0,
                "iCityId" => $request->iCityId ?? 0,
                "strVertical" => $request->strVertical,
                "strSubVertical" => $request->strSubVertical,
                "strSI" => $request->strSI,
                "strEngineer" => $request->strEngineer,
                "strCommissionedIn" => $request->strCommissionedIn,
                "iSystemId" => $request->iSystemId ?? 0,
                "strPanel" => $request->strPanel,
                "strPanelQuantity" => $request->strPanelQuantity,
                "strDevices" => $request->strDevices,
                "strDeviceQuantity" => $request->strDeviceQuantity,
                "strOtherComponents" => $request->strOtherComponents,
                "strBOQ" => $request->strBOQ,
                "strAMC" => $request->strAMC,
                "strOtherInformation" => $request->strOtherInformation,
                "updated_at" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            // dd($data);
            $projectProfileId = 0;
            if ($request->projectProfileId > 0) {
                $projectProfileId = $request->projectProfileId;
                ProjectProfile::where('iTicketId', '=', $id)->update($data);
            } else {
                $getid = ProjectProfile::create($data);
                $projectProfileId = $getid->id;
            }
            $GetInsertId = $id;

            $img = "";
            if ($request->hasFile('strBOQUpload')) {

                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/Project/BOQ_Upload/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('strBOQUpload') as $docfile) {
                    $image = $docfile;
                    $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();

                    $image->move($destinationPath, $img);
                    $docArr = array(
                        "projectProfileId" => $projectProfileId,
                        "strBOQUpload" => $img,
                        "created_at" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    ProjectProfileFiles::create($docArr);
                }
            }

            if ($request->hasFile('CompletionDocumentUpload')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/Project/Completion_Document_Upload/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $imgName = "";
                foreach ($request->file('CompletionDocumentUpload') as $imagefile) {
                    $image = $imagefile;
                    $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $imgName);
                    $docArr = array(
                        "projectProfileId" => $projectProfileId,
                        "CompletionDocumentUpload" => $imgName,
                        "created_at" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    ProjectProfileFiles::create($docArr);
                }
            }

            return redirect()->route('projects.index')->with('Success', 'Project Updated Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        // dd($Id);
        if (Auth::User()->role_id == 2) {

            $Documents = ProjectProfileFiles::where(["projectProfileId" => $Id])->get();
            $root = $_SERVER['DOCUMENT_ROOT'];
            $BOQ_UploadPath = $root . '/Project/BOQ_Upload/';
            if (!empty($Documents)) {
                foreach ($Documents as $document) {
                    if ($document->strBOQUpload != "") {
                        if (file_exists($BOQ_UploadPath . $document->strBOQUpload)) {
                            unlink($BOQ_UploadPath . $document->strBOQUpload);
                        }
                    }
                    ProjectProfileFiles::where(["projectProfileId" => $document->projectProfileId])->delete();
                }
            }

            $root = $_SERVER['DOCUMENT_ROOT'];
            $Completion_Document_UploadPath = $root . '/Project/Completion_Document_Upload/';
            if (!empty($Documents)) {
                foreach ($Documents as $document) {
                    if ($document->CompletionDocumentUpload != "") {
                        if (file_exists($Completion_Document_UploadPath . $document->CompletionDocumentUpload)) {
                            unlink($Completion_Document_UploadPath . $document->CompletionDocumentUpload);
                        }
                    }
                    ProjectProfileFiles::where(["projectProfileId" => $document->projectProfileId])->delete();
                }
            }

            ProjectProfile::where('projectProfileId', '=', $Id)->delete();

            return redirect()->route('projects.index')->with('Success', 'Project Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }

    public function AddDocument(Request $request)
    {
        $datas = ProjectProfile::where(['isDelete' => 0, "iStatus" => 1, 'iTicketId' => $request->iTicketId])->first();

        $img = "";
        if ($request->hasFile('strBOQUpload')) {

            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationPath = $root . '/Project/BOQ_Upload/';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            foreach ($request->file('strBOQUpload') as $docfile) {
                $image = $docfile;
                $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();

                $image->move($destinationPath, $img);
                $docArr = array(
                    "iTicketId" => $request->iTicketId ?? 0,
                    "projectProfileId" => $datas->projectProfileId ?? 0,
                    "strBOQUpload" => $img,
                    "iUserId" => Auth::user()->id ?? 0,
                    "created_at" => date('Y-m-d H:i:s'),
                    "strIP" => $request->ip()
                );
                ProjectProfileFiles::create($docArr);
            }
        }

        if ($request->hasFile('CompletionDocumentUpload')) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationPath = $root . '/Project/Completion_Document_Upload/';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $imgName = "";
            foreach ($request->file('CompletionDocumentUpload') as $imagefile) {
                $image = $imagefile;
                $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $imgName);
                $docArr = array(
                    "iTicketId" => $request->iTicketId ?? 0,
                    "projectProfileId" => $datas->projectProfileId ?? 0,
                    "CompletionDocumentUpload" => $imgName,
                    "iUserId" => Auth::user()->id ?? 0,
                    "created_at" => date('Y-m-d H:i:s'),
                    "strIP" => $request->ip()
                );
                ProjectProfileFiles::create($docArr);
            }
        }

        return back()->with('Success', 'Updated Successfully!.');
    }

    public function openDocument(Request $request, $id)
    {
        $root = $_SERVER['DOCUMENT_ROOT'];
        $Documents = ProjectProfileFiles::where(["projectProfileFilesId" => $id])->first();
        // dd($Documents);

        if ($Documents->strBOQUpload) {
            $destinationPath =  asset('Project/BOQ_Upload/') . '/' . $Documents->strBOQUpload;
            $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);
            // dd($destinationPath);
            $encodedPath = urlencode($destinationPath);
            if ($ext == 'pdf')
                echo   "<iframe src='" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
            else
                echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $encodedPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        } else {
            // dd('else');
            $destinationPath =  asset('Project/Completion_Document_Upload/') . '/' . $Documents->CompletionDocumentUpload;
            echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        }
    }
    public function deletedoc(Request $request, $id)
    {

        if (Auth::User()->role_id == 2) {
            $Documents = ProjectProfileFiles::where(["projectProfileFilesId" => $id])->first();
            if ($Documents->strBOQUpload) {

                $destinationPath =  asset('/Project/BOQ_Upload/') . '/' . $Documents->strBOQUpload;
                if (file_exists($destinationPath)) {
                    unlink($destinationPath);
                }

                ProjectProfileFiles::where(["projectProfileFilesId" => $Documents->projectProfileFilesId])->delete();
            } else {

                $destinationPath =  asset('/Project/Completion_Document_Upload/') . '/' . $Documents->CompletionDocumentUpload;
                if (file_exists($destinationPath)) {
                    unlink($destinationPath);
                }

                ProjectProfileFiles::where(["projectProfileFilesId" => $Documents->projectProfileFilesId])->delete();
            }
            return back()->with('Success', 'Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }
}
