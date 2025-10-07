<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Loginlog;
use App\Models\CompanyMaster;
use App\Models\CallAttendent;
use App\Models\Component;
use App\Models\WlUser;
use App\Models\SubComponent;
use App\Models\CompanyInfo;
use App\Models\Role;
use App\Models\TicketMaster;
use App\Models\CompanyClient;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //, 'checksession'
        $this->middleware(['auth', 'ipcheck']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $GetCompanyFromFront = session('GetCompanyFromFront');
        // dd($GetCompanyFromFront);
        if (Session::get('CompanyId')) {
            $GetCompany_FromFront = session('CompanyId');
        } else {
            // dd(Auth::user()->id);
            $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();

            if ($companyUser) {
                $request->session()->put('CompanyId', $companyUser->iCompanyId);
            }
        }
        // dd($GetCompany_FromFront);
        // $this->switchuser($request);
        $session = Auth::user()->role_id;

        $userId = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

        $userdata = User::whereId($userId)->first();
        // dd($userdata);

        $menuArray = array();
        $login = Loginlog::create(['userId' => $userdata->id, 'action' => 'Login', "strIP" => $request->ip(), "strEntryDate" => date('Y-m-d H:i:s')]);
        if (Auth::user()->role_id == 1) {
            // $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])->count();
            // $CallAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1])->count();
            // $CallAttendentLoginlog = Loginlog::select('loginlog.*', 'users.first_name', 'users.last_name')
            //     ->join('users', 'users.id', '=', 'loginlog.userId')
            //     ->where(['status' => 1, 'users.role_id' => 3])
            //     ->orderBy('loginlog.id', 'DESC')->limit(5)->get();

            //, compact('CompanyMaster', 'CallAttendent', 'CallAttendentLoginlog')
            $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])->orderBy('strOEMCompanyName', 'ASC')->get();
            return view('admin.home', compact('yearList', 'CompanyMaster'));
        } else if (Auth::user()->role_id == 2) {
            $strOEMCompanyName = "";
            $iCompanyId = 0;
           
            if (Auth::user()->role_id == 2 && Auth::user()->isCanSwitchProfile == 1) {
                $request->session()->put('exeLevel', 2);
            } else {
               
                if (!$request->session()->get('CompanyId')) {
                    $CompanyMaster = DB::table('subsidiary_company')->select(
                        'subsidiary_company.*',
                        'companymaster.strOEMCompanyName',
                        'companymaster.iCompanyId'
                    )
                        ->where(['subsidiary_company.isDelete' => 0, 'subsidiary_company.iStatus' => 1, 'subsidiary_company.userId' => Auth::user()->id])
                        ->join('companymaster', 'companymaster.iCompanyId', '=', 'subsidiary_company.company_id')
                        ->get();
                    if (!$CompanyMaster->isEmpty()) {
                        return view('wladmin.afterlogin', compact('CompanyMaster'));
                    }
                }
            }
 
            $request->session()->put('Switch', "0");

            if (Session::get('CompanyId')) {
                
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => Session::get('CompanyId')])->first();
                $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                $iCompanyId = $CompanyMaster->iCompanyId;
            } else {
                
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iUserId' => Auth::user()->id])->first();

                if ($CompanyMaster == null || $CompanyMaster == "") {

                    $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();

                    if ($companyUser) {
                       
                        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();

                        $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                        $iCompanyId = $CompanyMaster->iCompanyId;
                        $request->session()->put('iRoleId', $companyUser->iRoleId);

                        $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();

                        foreach ($MenuList as $menu) {
                            $menuArray[] = $menu->permission_id;
                        }

                        $request->session()->put('menuList', $menuArray);
                    } else {

                        $CompanyMaster = DB::table('multiplecompanyrole')->select(
                            'multiplecompanyrole.*',
                            'companymaster.strOEMCompanyName',
                            'roles.name'
                        )
                            ->where(['multiplecompanyrole.isDelete' => 0, 'multiplecompanyrole.iStatus' => 1, 'multiplecompanyrole.userid' => Auth::user()->id])
                            ->join('companymaster', 'companymaster.iCompanyId', '=', 'multiplecompanyrole.iOEMCompany')
                            ->join('roles', 'roles.id', '=', 'multiplecompanyrole.iRoleId')
                            ->get();

                        return view('call_attendant.afterlogin', compact('CompanyMaster'));
                    }
                } else {
                    
                    $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                    $iCompanyId = $CompanyMaster->iCompanyId;
                }
            }

            if ($CompanyMaster == null || $CompanyMaster == "") {

                // $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();

                // $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();

                // $request->session()->put('iRoleId', $companyUser->iRoleId);

                // $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                //     ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();

                // foreach ($MenuList as $menu) {
                //     $menuArray[] = $menu->permission_id;
                // }
                // $request->session()->put('menuList', $menuArray);
            } else {

                if (Session::get('CompanyId')) {

                    // $Company = DB::table('multiplecompanyrole')->where(['iOEMCompany' => session('CompanyId')])
                    //     ->first();

                    // if ($Company) {
                    $CompanyMaster = DB::table('multiplecompanyrole')->select(
                        'multiplecompanyrole.*',
                        'companymaster.strOEMCompanyName',
                        'roles.name'
                    )
                        ->where(['multiplecompanyrole.isDelete' => 0, 'multiplecompanyrole.iStatus' => 1, 'multiplecompanyrole.userid' => Auth::user()->id, "iOEMCompany" => Session::get('CompanyId')])
                        ->join('companymaster', 'companymaster.iCompanyId', '=', 'multiplecompanyrole.iOEMCompany')
                        ->join('roles', 'roles.id', '=', 'multiplecompanyrole.iRoleId')
                        ->first();

                    if ($CompanyMaster) {
  
                        $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                        $iCompanyId = $CompanyMaster->iOEMCompany;
                       
                        $MenuList = DB::table('role_has_permissions')->where(['role_id' => $CompanyMaster->iRoleId])
                            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                            ->where(function ($query) {
                                    $query->where('permissions.module_id', 0)
                                          ->orWhereIn('permissions.module_id', function ($subQuery) {
                                              $subQuery->select('oem_company_modules.iModuleId')
                                                       ->from('oem_company_modules')
                                                       ->where('oem_company_modules.iOEMCompany', Session::get('CompanyId'));
                                          });
                                })
                                ->select('permissions.*', 'role_has_permissions.*')
                                ->get();
                                
                    } else {
                    
                        $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
                        if ($companyUser) {
                         
                            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
                            $request->session()->put('iRoleId', $companyUser->iRoleId);
                            $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
                            foreach ($MenuList as $menu) {
                                $menuArray[] = $menu->permission_id;
                            }
                            $request->session()->put('menuList', $menuArray);
                        } else {
                           
                            $MenuList = DB::table('permissions') ->where('module_id', 0)
                                        ->orWhere(function ($query) {
                                            $query->whereIn('module_id', function ($subquery) {
                                                $subquery->select('iModuleId')
                                                    ->from('oem_company_modules')
                                                    ->where('iOEMCompany', Session::get('CompanyId'))
                                                    ->whereColumn('iModuleId', 'permissions.module_id');
                                            });
                                        })->get();
                                  //      dd($MenuList);
                        }
                         
                    }
                  
                } else {
   
                    $MenuList = DB::table('permissions')->where('module_id', 0)
                                        ->orWhere(function ($query) {
                                            $query->whereIn('module_id', function ($subquery) {
                                                $subquery->select('iModuleId')
                                                    ->from('oem_company_modules')
                                                    ->where('iOEMCompany', Session::get('CompanyId'))
                                                    ->whereColumn('iModuleId', 'permissions.module_id');
                                            });
                                            })->get();
                }
                 //dd($MenuList);
                foreach ($MenuList as $menu) {
                    $menuArray[] = $menu->id;
                }
                $request->session()->put('menuList', $menuArray);
                $request->session()->put('iRoleId', '0');
            }

            // dd(Session('menuList'));
            $request->session()->put('CompanyName', $strOEMCompanyName);
            $request->session()->put('CompanyId', $iCompanyId);
            $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
            $totalTicket = DB::table('ticketmaster')
                ->where('OemCompannyId', $iCompanyId)
                ->count();
            $openTicket = DB::table('ticketmaster')
                ->where(['OemCompannyId' => $iCompanyId, 'finalStatus' => '0'])
                ->count();
            $custCompany = DB::table('companyclient')
                ->where('iCompanyId', $iCompanyId)->where('iStatus', '1')
                ->where('isDelete', '0')
                ->count();

            $customer = DB::table('ticketmaster')
                ->where('ticketmaster.OemCompannyId', $iCompanyId)
                ->groupBy('ticketmaster.CustomerMobile')
                ->get();




            $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
                (
                select count(*) as t1 from ticketmaster where
                ticketmaster.OemCompannyId = " . $iCompanyId . "
                UNION ALL
                select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
                and ticketmaster.OemCompannyId = " . $iCompanyId . ") tbl");
            $ticketCall = $ticketCallQuery[0]->tCall;
            $dashboarCount = array('call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custCompany' => $custCompany, 'customer' => count($customer));
            $companyInfo = CompanyInfo::select('headerColor', 'menuColor', 'menubgColor', 'strLogo')->where('iCompanyId', $iCompanyId)->first();

            if (isset($companyInfo->strLogo) && $companyInfo->strLogo != "") {
                $request->session()->put('CompanyLogo', env('APP_URL') . '/WlLogo/' . $companyInfo->strLogo);
            } else {
                $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
            }
            $this->changeCssColor($request, $companyInfo);

            return view('wladmin.index', compact('yearList', 'dashboarCount'));
        } else if (Auth::user()->role_id == 3) {
            
            $menuArray = array();
            $User = User::where(['status' => 1, 'id' => Auth::user()->id])->first();
            $strOEMCompanyName = "";
            $iCompanyId = 0;
            if ($User->isCanSwitchProfile == 1) {

                $CompanyMaster = DB::table('multiplecompanyrole')->select(
                    'multiplecompanyrole.*',
                    'companymaster.strOEMCompanyName',
                    'roles.name'
                )
                    ->where(['multiplecompanyrole.isDelete' => 0, 'multiplecompanyrole.iStatus' => 1, 'multiplecompanyrole.userid' => Auth::user()->id])
                    ->join('companymaster', 'companymaster.iCompanyId', '=', 'multiplecompanyrole.iOEMCompany')
                    ->join('roles', 'roles.id', '=', 'multiplecompanyrole.iRoleId')
                    ->get();
                //dd($CompanyMaster);
                if (!$CompanyMaster->isEmpty()) {

                    $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();

                    if ($companyUser) {
                        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
                        // dd($CompanyMaster);
                        $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                        $iCompanyId = $companyUser->iCompanyId;
                        $request->session()->put('iRoleId', $companyUser->iRoleId);
                        $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
                        foreach ($MenuList as $menu) {
                            $menuArray[] = $menu->permission_id;
                        }
                        $request->session()->put('menuList', $menuArray);
                        // print_r($menuArray);
                        // dd(Session::get("menuList"));
                    } else {
                        return view('call_attendant.afterlogin', compact('CompanyMaster'));
                    }
                } else {
                    $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
                    //Session::get('CompanyId')
                    $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
                    
                    if ($CompanyMaster == null || $CompanyMaster == "") {
                        $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
                        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
                        $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                        $iCompanyId = $companyUser->iCompanyId;
                        $request->session()->put('iRoleId', $companyUser->iRoleId);
                        $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();

                        foreach ($MenuList as $menu) {
                            $menuArray[] = $menu->permission_id;
                        }
                        $request->session()->put('menuList', $menuArray);
                    } else {

                        if (Session::get('CompanyId')) {

                            $Company = DB::table('multiplecompanyrole')->where(['iOEMCompany' => session('CompanyId')])
                                ->first();
                            
                            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => session('CompanyId')])->first();
                            $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
                            $iCompanyId = $companyUser->iCompanyId;
                            $request->session()->put('iRoleId', $companyUser->iRoleId);
                            $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
                            
                            $strOEMCompanyName = $CompanyMaster->strOEMCompanyName;
                            $iCompanyId = session('CompanyId');
                            //if($Company){
                                //$MenuList = DB::table('role_has_permissions')->where(['role_id' => $Company->iRoleId])
                                //    ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
                                
                            //}  else {
                                //$MenuList = DB::table('permissions')->get();    
                            //}
                        } else {

                            $MenuList = DB::table('permissions')->get();
                         }

                        foreach ($MenuList as $menu) {
                            $menuArray[] = $menu->id;
                        }
                        $request->session()->put('menuList', $menuArray);
                        $request->session()->put('iRoleId', '0');
                    }
                }
                $request->session()->put('CompanyName', $strOEMCompanyName);
                $request->session()->put('CompanyId', $iCompanyId);

                $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
                $totalTicket = DB::table('ticketmaster')
                    ->where('OemCompannyId', $CompanyMaster->iCompanyId)
                    ->count();
                $openTicket = DB::table('ticketmaster')
                    ->where(['OemCompannyId' => $CompanyMaster->iCompanyId, 'finalStatus' => '0'])
                    ->count();
                $custCompany = DB::table('companyclient')
                    ->where('iCompanyId', $CompanyMaster->iCompanyId)->where('iStatus', '1')
                    ->where('isDelete', '0')
                    ->count();

                $customer = DB::table('ticketmaster')
                    ->where('ticketmaster.OemCompannyId', $CompanyMaster->iCompanyId)
                    ->groupBy('ticketmaster.CustomerMobile')
                    ->get();
                $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
                    (
                    select count(*) as t1 from ticketmaster where
                    ticketmaster.OemCompannyId = " . $CompanyMaster->iCompanyId . "
                    UNION ALL
                    select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
                    and ticketmaster.OemCompannyId = " . $CompanyMaster->iCompanyId . ") tbl");
                $ticketCall = $ticketCallQuery[0]->tCall;
                $dashboarCount = array('call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custCompany' => $custCompany, 'customer' => count($customer));

                $companyInfo = CompanyInfo::select('headerColor', 'menuColor', 'menubgColor', 'strLogo')->where('iCompanyId', $iCompanyId)->first();

                if (isset($companyInfo->strLogo) && $companyInfo->strLogo != "") {
                    $request->session()->put('CompanyLogo', env('APP_URL') . '/WlLogo/' . $companyInfo->strLogo);
                } else {
                    $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
                }
                $this->changeCssColor($request, $companyInfo);
                $request->session()->put('exeLevel', 2);

                return view('wladmin.index', compact('yearList', 'dashboarCount'));
            } else {

                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])->get();
                $statemasters = DB::table('statemaster')
                    ->where(['isDelete' => 0, "iStatus" => 1])
                    ->orderBy('strStateName', 'ASC')
                    ->get();
                $citymasters = DB::table('citymaster')
                    ->where(['isDelete' => 0, "iStatus" => 1])
                    ->orderBy('strCityName', 'ASC')
                    ->get();
                $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                    ->first();

                $request->session()->put('exeLevel', $callAttendent->iExecutiveLevel);
                return view('call_attendant.home', compact('CompanyMaster', 'statemasters', 'citymasters'));


                // $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
                // $totalTicket = DB::table('ticketmaster')
                //     ->where('OemCompannyId', $CompanyMaster->iCompanyId)
                //     ->count();
                // $openTicket = DB::table('ticketmaster')
                //     ->where(['OemCompannyId' => $CompanyMaster->iCompanyId, 'finalStatus' => '0'])
                //     ->count();
                // $custCompany = DB::table('companyclient')
                //     ->where('iCompanyId', $CompanyMaster->iCompanyId)->where('iStatus', '1')
                //     ->where('isDelete', '0')
                //     ->count();

                // $customer = DB::table('ticketmaster')
                //     ->where('ticketmaster.OemCompannyId', $CompanyMaster->iCompanyId)
                //     ->groupBy('ticketmaster.CustomerMobile')
                //     ->get();
                // $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])->get();
                // $statemasters = DB::table('statemaster')
                //     ->where(['isDelete' => 0, "iStatus" => 1])
                //     ->orderBy('strStateName', 'ASC')
                //     ->get();
                // $citymasters = DB::table('citymaster')
                //     ->where(['isDelete' => 0, "iStatus" => 1])
                //     ->orderBy('strCityName', 'ASC')
                //     ->get();
                // $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                //     ->first();

                // $request->session()->put('exeLevel', $callAttendent->iExecutiveLevel);
                // return view('call_attendant.home', compact('CompanyMaster', 'statemasters', 'citymasters'));
            }
        } else {

            Auth::logout();
            Session::forget('user');
            return redirect()->route('login');
        }
        
    }

    // public function adminHome()
    // {
    //     return view('adminHome');
    // }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('admin.profile');
    }

    public function getCallChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
        $callLog = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw('count(*) as `total`'), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"), DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();

        $montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $totalSum = 0;
        foreach ($callLog as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $totalSum =   (int)$totalSum + (int)$call->total;
            }
        }
        foreach ($callLog as $call) {
            if (array_key_exists($call->month, $montharray)) {
                //$montharray[$call->month] = ($call->total / 100) * $totalSum;
                $montharray[$call->month] = $call->total;
                $call->total;
            }
        }
        return json_encode($montharray);
    }

    public function getLineChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
        $calllevel = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw('count(*) as `total`'), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"), DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();
             // ->toSql();
        // dd($calllevel);
        $totalSum = DB::table('ticketmaster')->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw("count(*) as `total`"), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->groupBy('month-year')
            ->orderBy('strEntryDate', 'ASC')->get();
        
        $montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $monthvisetotalarray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentagearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $month = '';
        foreach ($calllevel as $call) {
            if (array_key_exists($call->month, $montharray)) {
                //$montharray[$call->month] = round(($call->total * 100) / $totalSum,2);
                $montharray[$call->month] = $call->total;
            }
        }
        
        foreach ($totalSum as $sum) {
            if (array_key_exists($sum->month, $monthvisetotalarray)) {
                $monthvisetotalarray[$sum->month] = $sum->total;
            }
        }
        
        foreach ($monthvisetotalarray as $key => $val) {
            if (array_key_exists($key, $montharray)) {

                $percentagearray[$key] = $val == 0 ? 0 : ($montharray[$key] * 100) / $val;
            }
        }
        $dataArr = array();
        foreach ($percentagearray as $key => $val) {
            $dataArr[] = array(
                "Count" => $val,
                "key" => $key
            );
        }
        /*$callSameDayResolve = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw('count(*) as `total`'), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"), DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->where('ComplainDate', '>', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'))
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();*/
        
        $bindings = array_fill(0, 12, $datefrom);
        $bindings = array_merge($bindings, array_fill(0, 12, $dateto));
        
       /* $callSameDayResolve = DB::select(DB::raw("
                SELECT 
                    iTicketId,
                    MIN(first_open_time) AS first_open_time,
                    MIN(first_close_time) AS first_close_time,
                    MIN(reopen_time) AS reopen_time,
                    MAX(final_close_time) AS final_close_time,
                    
                    MIN(initial_tat_minutes) AS initial_tat_minutes,
                    MIN(reopen_tat_minutes) AS reopen_tat_minutes,
                    MIN(initial_tat_minutes) + MIN(reopen_tat_minutes) AS total_tat_minutes,
                    (MIN(initial_tat_minutes) + MIN(reopen_tat_minutes)) / 60 AS total_tat_hours,
                    
                    -- New columns
                    DATE_FORMAT(MIN(entry_date), '%Y-%m') AS `month-year`,
                    COUNT(*) AS total,
                    DATE_FORMAT(MIN(entry_date), '%b') AS `month`,
                    DATE_FORMAT(MIN(entry_date), '%c') AS `monthNo`
        
                FROM (
                    SELECT 
                        iTicketId,
                        iTicketLogId,
                        ticketstattime AS first_open_time,
                        ticketFirtCloseTime AS first_close_time,
                        ticketreopoentime AS reopen_time,
                        ticketlastCloseTime AS final_close_time,
                        TIMESTAMPDIFF(MINUTE, ticketstattime, ticketFirtCloseTime) AS initial_tat_minutes,
                        TIMESTAMPDIFF(MINUTE, ticketreopoentime, ticketlastCloseTime) AS reopen_tat_minutes,
                        strEntryDate AS entry_date
                    FROM (
                        SELECT 
                            tbl1.iTicketId,
                            tbl1.iTicketLogId,
                            (SELECT MIN(tm.oldStatusDatetime) 
                             FROM ticketmaster tm 
                             WHERE tm.iTicketId = tbl1.iTicketId 
                             AND tm.oldStatusDatetime IS NOT NULL
                             AND tm.oldStatusDatetime != '0000-00-00 00:00:00'
                             AND tm.strEntryDate BETWEEN '".$datefrom."' AND '".$dateto."'
                            ) AS ticketstattime,
                            (SELECT MIN(tl.oldStatusDatetime)
                             FROM ticketlog tl
                             WHERE tl.iticketId = tbl1.iTicketId
                             AND tl.iStatus IN (1, 4, 5)
                             AND tl.oldStatusDatetime IS NOT NULL
                             AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                             AND tl.strEntryDate BETWEEN '".$datefrom."' AND '".$dateto."'
                             AND tl.oldStatusDatetime > tbl1.open_time
                            ) AS ticketFirtCloseTime,
                            (SELECT tl.oldStatusDatetime
                             FROM ticketlog tl
                             WHERE tl.iticketId = tbl1.iTicketId
                             AND tl.iStatus = 3
                             AND tl.oldStatusDatetime IS NOT NULL
                             AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                             AND tl.strEntryDate BETWEEN '".$datefrom."' AND '".$dateto."'
                             ORDER BY tl.oldStatusDatetime ASC
                             LIMIT 1
                            ) AS ticketreopoentime,
                            (SELECT MAX(tl.oldStatusDatetime)
                             FROM ticketlog tl
                             WHERE tl.iticketId = tbl1.iTicketId
                             AND tl.iStatus IN (1, 4, 5)
                             AND tl.oldStatusDatetime IS NOT NULL
                             AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                             AND tl.strEntryDate BETWEEN '".$datefrom."' AND '".$dateto."'
                            ) AS ticketlastCloseTime,
                            tbl1.strEntryDate
                        FROM (
                            SELECT DISTINCT 
                                tm.iTicketId,
                                tl.iTicketLogId,
                                (SELECT MIN(tm2.oldStatusDatetime) 
                                 FROM ticketmaster tm2 
                                 WHERE tm2.iTicketId = tm.iTicketId
                                 AND tm2.oldStatusDatetime IS NOT NULL
                                 AND tm2.oldStatusDatetime != '0000-00-00 00:00:00'
                                 AND tm2.strEntryDate BETWEEN '".$datefrom."' AND '".$dateto."'
                                ) AS open_time,
                                tl.strEntryDate
                            FROM ticketmaster tm
                            JOIN ticketlog tl ON tm.iTicketId = tl.iticketId
                            WHERE tm.finalStatus = 1
                            AND tl.iStatus = 3
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN '".$datefrom."' AND '".$dateto."'
                        ) tbl1
                    ) tbl2
                    WHERE ticketstattime IS NOT NULL
                    AND ticketFirtCloseTime IS NOT NULL
                    AND ticketreopoentime IS NOT NULL
                    AND ticketlastCloseTime IS NOT NULL
                ) final_data
                GROUP BY iTicketId
                HAVING total_tat_minutes < 1440
                ORDER BY total_tat_minutes DESC
            ")); */
        /*$callSameDayResolve = DB::select(DB::raw("
            SELECT 
                 iTicketId,
                MIN(first_open_time) AS first_open_time,
                MIN(first_close_time) AS first_close_time,
                MIN(reopen_time) AS reopen_time,
                MAX(final_close_time) AS final_close_time,
                
                IFNULL(MIN(initial_tat_minutes),0) AS initial_tat_minutes,
                IFNULL(MIN(reopen_tat_minutes),0) AS reopen_tat_minutes,
                IFNULL(MIN(initial_tat_minutes),0) + IFNULL(MIN(reopen_tat_minutes),0) AS total_tat_minutes,
                (IFNULL(MIN(initial_tat_minutes),0) + IFNULL(MIN(reopen_tat_minutes),0)) / 60 AS total_tat_hours,
                
                DATE_FORMAT(MIN(entry_date), '%Y-%m') AS `month-year`,
                COUNT(*) AS total,
                DATE_FORMAT(MIN(entry_date), '%b') AS month,
                DATE_FORMAT(MIN(entry_date), '%c') AS monthNo
            FROM (
                SELECT 
                    iTicketId,
                    iTicketLogId,
                    ticketstattime AS first_open_time,
                    ticketFirtCloseTime AS first_close_time,
                    ticketreopoentime AS reopen_time,
                    ticketlastCloseTime AS final_close_time,
                    TIMESTAMPDIFF(MINUTE, ticketstattime, ticketFirtCloseTime) AS initial_tat_minutes,
                    TIMESTAMPDIFF(MINUTE, ticketreopoentime, ticketlastCloseTime) AS reopen_tat_minutes,
                    strEntryDate AS entry_date
                FROM (
                    SELECT 
                        tbl1.iTicketId,
                        tbl1.iTicketLogId,
                        (
                            SELECT MIN(tm.oldStatusDatetime) 
                            FROM ticketmaster tm 
                            WHERE tm.iTicketId = tbl1.iTicketId 
                            AND tm.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                        ) AS ticketstattime,
                        (
                            SELECT MIN(tl.oldStatusDatetime)
                            FROM ticketlog tl
                            LEFT JOIN ticketmaster tm ON tl.iticketId = tm.iTicketId
                            WHERE tl.iticketId = tbl1.iTicketId
                            AND tl.iStatus IN (1, 4, 5)
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN ? AND ?
                            AND tl.oldStatusDatetime > tbl1.open_time
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                        ) AS ticketFirtCloseTime,
                        (
                            SELECT tl.oldStatusDatetime
                            FROM ticketlog tl
                            LEFT JOIN ticketmaster tm ON tl.iticketId = tm.iTicketId
                            WHERE tl.iticketId = tbl1.iTicketId
                            AND tl.iStatus = 3
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                            ORDER BY tl.oldStatusDatetime ASC
                            LIMIT 1
                        ) AS ticketreopoentime,
                        (
                            SELECT MAX(tl.oldStatusDatetime)
                            FROM ticketlog tl
                            LEFT JOIN ticketmaster tm ON tl.iticketId = tm.iTicketId
                            WHERE tl.iticketId = tbl1.iTicketId
                            AND tl.iStatus IN (1, 4, 5)
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                        ) AS ticketlastCloseTime,
                        tbl1.strEntryDate
                    FROM (
                        SELECT DISTINCT 
                            tm.iTicketId,
                            tl.iTicketLogId,
                            (
                                SELECT MIN(tm2.oldStatusDatetime) 
                                FROM ticketmaster tm2 
                                WHERE tm2.iTicketId = tm.iTicketId
                                AND tm2.oldStatusDatetime IS NOT NULL
                                AND tm2.oldStatusDatetime != '0000-00-00 00:00:00'
                                AND tm2.strEntryDate BETWEEN ? AND ?
                                AND tm2.OemCompannyId = ?
                                AND tm2.LevelId = '1'
                                AND tm2.iLevel2CallAttendentId = '0'
                            ) AS open_time,
                            tl.strEntryDate
                        FROM ticketmaster tm
                        LEFT JOIN ticketlog tl ON tm.iTicketId = tl.iticketId
                        WHERE tm.finalStatus IN (1, 4, 5)
                        AND tl.iStatus IN (1, 4, 5)
                        AND tl.oldStatusDatetime IS NOT NULL
                        AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                        AND tl.strEntryDate BETWEEN ? AND ?
                        AND tm.OemCompannyId = ?
                        AND tm.LevelId = '1'
                        AND tm.iLevel2CallAttendentId = '0'
                    ) tbl1
                ) tbl2
            ) final_data
            GROUP BY iTicketId
            HAVING total_tat_minutes < 1440
            ORDER BY total_tat_minutes DESC
        "), [
            // Bind values in the exact order of appearance above
            $datefrom, $dateto, Session::get('CompanyId'),   // 1 - ticketstattime
            $datefrom, $dateto, Session::get('CompanyId'),   // 2 - ticketFirtCloseTime
            $datefrom, $dateto, Session::get('CompanyId'),   // 3 - ticketreopoentime
            $datefrom, $dateto, Session::get('CompanyId'),   // 4 - ticketlastCloseTime
            $datefrom, $dateto, Session::get('CompanyId'),   // 5 - open_time
            $datefrom, $dateto, Session::get('CompanyId')    // 6 - outer WHERE
        ]);*/
       
        $companyId = Session::get('CompanyId');
        
        $callSameDayResolve = DB::select(DB::raw("
            SELECT 
                iTicketId,
                MIN(first_open_time) AS first_open_time,
                MIN(first_close_time) AS first_close_time,
                MIN(reopen_time) AS reopen_time,
                MAX(final_close_time) AS final_close_time,
                
                IFNULL(MIN(initial_tat_minutes),0) AS initial_tat_minutes,
                IFNULL(MIN(reopen_tat_minutes),0) AS reopen_tat_minutes,
                IFNULL(MIN(initial_tat_minutes),0) + IFNULL(MIN(reopen_tat_minutes),0) AS total_tat_minutes,
                (IFNULL(MIN(initial_tat_minutes),0) + IFNULL(MIN(reopen_tat_minutes),0)) / 60 AS total_tat_hours,
                
                DATE_FORMAT(MIN(entry_date), '%Y-%m') AS `month-year`,
                COUNT(*) AS total,
                DATE_FORMAT(MIN(entry_date), '%b') AS month,
                DATE_FORMAT(MIN(entry_date), '%c') AS monthNo
            FROM (
                SELECT 
                    iTicketId,
                    iTicketLogId,
                    ticketstattime AS first_open_time,
                    ticketFirtCloseTime AS first_close_time,
                    ticketreopoentime AS reopen_time,
                    ticketlastCloseTime AS final_close_time,
                    TIMESTAMPDIFF(MINUTE, ticketstattime, ticketFirtCloseTime) AS initial_tat_minutes,
                    TIMESTAMPDIFF(MINUTE, ticketreopoentime, ticketlastCloseTime) AS reopen_tat_minutes,
                    strEntryDate AS entry_date
                FROM (
                    SELECT 
                        tbl1.iTicketId,
                        tbl1.iTicketLogId,
                        (
                            SELECT MIN(tm.oldStatusDatetime) 
                            FROM ticketmaster tm 
                            WHERE tm.iTicketId = tbl1.iTicketId 
                            AND tm.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                        ) AS ticketstattime,
                        (
                            SELECT MIN(tl.oldStatusDatetime)
                            FROM ticketlog tl
                            LEFT JOIN ticketmaster tm ON tl.iticketId = tm.iTicketId
                            WHERE tl.iticketId = tbl1.iTicketId
                            AND tl.iStatus IN (1, 4, 5)
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN ? AND ?
                            AND tl.oldStatusDatetime > (
                                SELECT MIN(tm2.oldStatusDatetime) 
                                FROM ticketmaster tm2 
                                WHERE tm2.iTicketId = tbl1.iTicketId
                                AND tm2.finalStatus IN (1, 4, 5)
                                AND tm2.strEntryDate BETWEEN ? AND ?
                                AND tm2.OemCompannyId = ?
                                AND tm2.LevelId = '1'
                                AND tm2.iLevel2CallAttendentId = '0'
                            )
                            AND tm.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                        ) AS ticketFirtCloseTime,
                        (
                            SELECT tl.oldStatusDatetime
                            FROM ticketlog tl
                            LEFT JOIN ticketmaster tm ON tl.iticketId = tm.iTicketId
                            AND tl.iStatus = 3
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN ? AND ?
                            WHERE tl.iticketId = tbl1.iTicketId
                            AND tm.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                            ORDER BY tl.oldStatusDatetime ASC
                            LIMIT 1
                        ) AS ticketreopoentime,
                        (
                            SELECT MAX(tl.oldStatusDatetime)
                            FROM ticketlog tl
                            LEFT JOIN ticketmaster tm ON tl.iticketId = tm.iTicketId
                            AND tl.iStatus IN (1, 4, 5)
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                            AND tl.strEntryDate BETWEEN ? AND ?
                            WHERE tl.iticketId = tbl1.iTicketId
                            AND tm.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                        ) AS ticketlastCloseTime,
                        tbl1.strEntryDate
                    FROM (
                        SELECT 
                            tm.iTicketId,
                            tl.iTicketLogId,
                            (
                                SELECT MIN(tm2.oldStatusDatetime) 
                                FROM ticketmaster tm2 
                                WHERE tm2.iTicketId = tm.iTicketId
                                AND tm2.finalStatus IN (1, 4, 5)
                                AND tm2.strEntryDate BETWEEN ? AND ?
                                AND tm2.OemCompannyId = ?
                                AND tm2.LevelId = '1'
                                AND tm2.iLevel2CallAttendentId = '0'
                            ) AS open_time,
                            tm.finalStatus,
                            tm.strEntryDate
                        FROM 
                            ticketmaster tm
                        LEFT JOIN 
                            ticketlog tl ON tm.iTicketId = tl.iticketId
                            AND tl.iStatus IN (1, 4, 5)
                            AND tl.oldStatusDatetime IS NOT NULL
                            AND tl.oldStatusDatetime != '0000-00-00 00:00:00'
                        WHERE 
                            tm.finalStatus IN (1, 4, 5)
                            AND tm.strEntryDate BETWEEN ? AND ?
                            AND tm.OemCompannyId = ?
                            AND tm.LevelId = '1'
                            AND tm.iLevel2CallAttendentId = '0'
                    ) tbl1
                ) tbl2
            ) final_data
        GROUP BY iTicketId
        HAVING total_tat_minutes < 1440
        ORDER BY total_tat_minutes DESC
        "), [
            // Bindings in exact order of appearance
            $datefrom, $dateto, $companyId,               // ticketstattime
            $datefrom, $dateto,                          // tl.strEntryDate
            $datefrom, $dateto, $companyId,              // subquery for open_time inside > clause
            $datefrom, $dateto, $companyId,              // join tm in ticketFirtCloseTime
            $datefrom, $dateto,                          // ticketreopoentime - tl.strEntryDate
            $datefrom, $dateto, $companyId,              // ticketreopoentime - tm
            $datefrom, $dateto,                          // ticketlastCloseTime - tl
            $datefrom, $dateto, $companyId,              // ticketlastCloseTime - tm
            $datefrom, $dateto, $companyId,              // open_time
            $datefrom, $dateto, $companyId               // final outer ticketmaster condition
        ]);


            // ->toSql();
        // dd($callSameDayResolve);*/
        // $sql = Str::replaceArray('?', $callSameDayResolve->getBindings(), $callSameDayResolve->toSql());
        // dd($sql);
        $monthSameDayResolvearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentageSameDayResolvearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        
        foreach ($callSameDayResolve as $call) {
            if (array_key_exists($call->month, $montharray)) {
                //$montharray[$call->month] = round(($call->total * 100) / $totalSum,2);
                //$monthSameDayResolvearray[$call->month] += $call->total;
                $monthSameDayResolvearray[$call->month] += 1;
            }
        }
        //dd($monthSameDayResolvearray);
        //foreach ($monthvisetotalarray as $key => $val) {
        //dd($montharray);
        foreach ($montharray as $key => $val) {
            if (array_key_exists($key, $monthSameDayResolvearray)) {

                $percentageSameDayResolvearray[$key] = $val == 0 ? 0 : ($monthSameDayResolvearray[$key] * 100) / $val;
            }
        }

        $allDataArr = array(
            "levelOne" => $dataArr,
            "samedaysolution" => $percentageSameDayResolvearray,
        );
        
        return json_encode($allDataArr);
        //return trim($month,',');
    }

    public function getLineChartSameDayResolve(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
        $calllevel = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw('count(*) as `total`'), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"), DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1, 2, 4))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->where('ComplainDate', '<=', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'))
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();
        echo "<pre>";
        print_r($calllevel);
        $totalSum = DB::table('ticketmaster')->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw("count(*) as `total`"), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"))->where('OemCompannyId', Session::get('CompanyId'))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->groupBy('month-year')
            ->orderBy('strEntryDate', 'ASC')->get();
        print_r($totalSum);
        exit;
        $montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $month = '';
        $iCounter = 0;
        foreach ($calllevel as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $montharray[$call->month] = ($call->total * 100) / $totalSum[$iCounter]->total;
            }
            $iCounter++;
        }
        return json_encode($montharray);
    }
    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        try {
            DB::beginTransaction();

            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with Success
            return back()->with('Success', 'Profile Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('Error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with Success
            return back()->with('Success', 'Password Changed Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('Error', $th->getMessage());
        }
    }

    public function logoutlog(Request $request)
    {
        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        $userdata = User::whereId($session)->first();
        if($userdata->isCanSwitchProfile == 1){
            User::whereId($session)->update(["role_id" => 2]);
        }
        $logout = Loginlog::create(['userId' => $userdata->id, 'action' => 'Logout', "strIP" => $request->ip(), "strEntryDate" => date('Y-m-d H:i:s')]);

        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function getPiaChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));

        $callLog = DB::table('ticketmaster')
            //->select(DB::raw('count(ticketmaster.iTicketId) as issueCount'),DB::raw('(select companyclient.CompanyName from companyclient where companyclient.iCompanyClientId=ticketmaster.iCompanyId) as CompanyName'))
            ->select(DB::raw('count(ticketmaster.iTicketId) as issueCount'), DB::raw("(select strSystem from `system` where `system`.iSystemId=ticketmaster.iSystemId) as strSystem"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('ticketmaster.strEntryDate', '=', $fMonth))
            ->orderBy('strSystem', 'ASC')
            ->groupBy('ticketmaster.iSystemId')
            ->get();

        return json_encode($callLog);
    }

    public function changeCssColor($request, $companyInfo)
    {

        $headerColor = "";
        $menuColor = "";
        $menubgColor = "";
        if (isset($companyInfo->headerColor) && $companyInfo->headerColor != "") {
            $headerColor = $companyInfo->headerColor . ' !important';
        } else {
            $headerColor = "#e9593e";
        }

        if (isset($companyInfo->menuColor) && $companyInfo->menuColor != "") {
            $menuColor = $companyInfo->menuColor . ' !important';
        } else {
            $menuColor = "#e9593e";
        }

        if (isset($companyInfo->menubgColor) && $companyInfo->menubgColor != "") {
            $menubgColor = $companyInfo->menubgColor . ' !important';
        } else {
            $menubgColor = "#e9593e";
        }

        $root = $_SERVER['DOCUMENT_ROOT'];
        $file = file_get_contents($root . "/global/assets/css/style-wl.css", "r");

        $file = str_replace("#header", $headerColor, $file);
        $file = str_replace("#menuhover", $menuColor, $file);
        $file = str_replace("#MenuBackground", $menubgColor, $file);
        $CompanyName =  session()->get('CompanyName');
        $destinationpath = $root . '/global/assets/css/';
        $string = str_replace(' ', '_', $CompanyName);
        $CompanyfileName = preg_replace('/[^A-Za-z0-9\-]/', '', $string) . '.css';
        if (file_exists($destinationpath . $CompanyfileName)) {
            unlink($destinationpath . $CompanyfileName);
        }
        if (!file_exists($destinationpath)) {
            mkdir($destinationpath, 0755, true);
        }
        $fp = fopen($destinationpath . $CompanyfileName, "wb");
        fwrite($fp, $file);
        fclose($fp);
        //$CompanyName =  session()->get('CompanyName');exit;
        //echo $file;
        //file_put_contents(file_path, data, flags, context)
        //exit;
        //return view('wladmin.style', compact('headerColor', 'menuColor','menubgColor'));
    }

    /*public function getDashboardCount(Request $request)
    {

        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iUserId' => Auth::user()->id])->first();

        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));

        $totalTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();
        $openTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereIn('finalStatus', array(0, 3))
            ->count();

        $custTDCompanies = CompanyClient::where("iCompanyId",'=',Session::get('CompanyId'));
                if ($request->fMonth != "") {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $fMonth = $request->fMonth;
                    
                    $endDate = Carbon::create($year, $fMonth, 1);
                    
                    // Get the last date and time of the month
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $custTDCompanies->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $endOfMonth);
                } else {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $fMonth = date('m');
                    $endDate = Carbon::create($year, $fMonth, 1);
                    
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $custTDCompanies->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $endOfMonth);
                    
                    
                }
                if ($request->yearId != "") {
                    $custTDCompanies->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
                } else {
                    $custTDCompanies->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
                }
                
        $custTDCompany = $custTDCompanies->count();
        
        $totalCustCompanies = CompanyClient::where("iCompanyId",'=',Session::get('CompanyId'));
                if ($request->fMonth != "") {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $month = $request->fMonth; // Assuming fMonth is in 'YYYY-MM' format
                    // $date = Carbon::createFromFormat('m', $month); // Parse the month
                    $endDate = Carbon::create($year, $month, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    //$endOfMonth = $date->endOfMonth(); // Get the last day of the month
                    $totalCustCompanies->where('strEntryDate', '<=', $endOfMonth);
                }else {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $fMonth = date('m');
                    $endDate = Carbon::create($year, $fMonth, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $totalCustCompanies->where('strEntryDate', '<=', $endOfMonth);
                }
                if ($request->yearId != "") {
                    $totalCustCompanies->where('strEntryDate', '<=', $dateto);
                } else {
                    $totalCustCompanies->where('strEntryDate', '<=', $dateto);
                }
        $totalCustCompany = $totalCustCompanies->count();
        
        $NewCompanies = CompanyClient::where("iCompanyId",'=',Session::get('CompanyId'));
            if ($request->fMonth != "") {
                $NewCompanies->whereMonth('strEntryDate', $request->fMonth);
                // $year = 0;
                // if($request->fMonth >=4 && $request->fMonth <= 12){
                //     $year = date('Y',strtotime($yeardetail->startDate));
                // } else {
                //     $year = date('Y',strtotime($yeardetail->endDate));
                // }
                // $month = $request->fMonth; // Assuming fMonth is in 'YYYY-MM' format
                // // $date = Carbon::createFromFormat('m', $month); // Parse the month
                // $endDate = Carbon::create($year, $month, 1);
                // $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                // //$endOfMonth = $date->endOfMonth(); // Get the last day of the month
                // $NewCompanies->where('strEntryDate', '<=', $endOfMonth);
            }else {
                // $year = 0;
                // if($request->fMonth >=4 && $request->fMonth <= 12){
                //     $year = date('Y',strtotime($yeardetail->startDate));
                // } else {
                //     $year = date('Y',strtotime($yeardetail->endDate));
                // }
                $fMonth = date('m');
                // $endDate = Carbon::create($year, $fMonth, 1);
                // $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                // $NewCompanies->where('strEntryDate', '<=', $endOfMonth);
                $NewCompanies->whereMonth('strEntryDate', date('m'))->whereYear('strEntryDate', date('Y')); 
            }
            // if ($request->fMonth != "") {
            //     $NewCompanies->whereMonth('strEntryDate', $request->fMonth);
            // } else {
            //     $startDate = Carbon::parse($datefrom);
            //     $endDate = Carbon::parse($dateto);
            //     // The date you want to check
            //     $checkDate = Carbon::parse(date('Y').'-'.date('m').'-01');
            //     //$checkDate = new DateTime(date('Y').'-'.date('m').'-01'); // This represents the month you're checking, e.g., August 2024

            //     if($checkDate >= $startDate && $checkDate <= $endDate){
            //         //$custCompanies->whereMonth('strEntryDate', date('m'))
            //         $NewCompanies->whereMonth('strEntryDate', date('m'))->whereYear('strEntryDate', date('Y')); 
            //     } else {
            //         $NewCompanies->whereMonth('strEntryDate', date('m'))->whereYear('strEntryDate', date('Y'));;    
            //     }
            // }
            if ($request->yearId != "") {
                $NewCompanies->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
            } else {
                $NewCompanies->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
            }   
        $NewCompany = $NewCompanies->count();
        //$NewCompany = $NewCompanies->toSql();
        // echo Session::get('CompanyId');
        // echo $request->fMonth;
        // echo "<br />";
        // echo $datefrom;
        // echo "<br />";
        // echo $dateto;
        // dd($NewCompany);
            
        // if($request->fMonth == null || $request->fMonth == ""){
        //     $pastMonthDate = date('Y-m-01');
        // } else {
        //     $pastMonthDate = date('Y-' . $request->fMonth . '-01');
        // }
        $endOfMonth = "";
        $year = 0;
        if($request->fMonth == null || $request->fMonth == ""){
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth ?? date('m');
            $endDate = Carbon::create($year, $fMonth, 1);

            // Get the last date and time of the month
            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
        } else {
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth ?? date('m');
            $endDate = Carbon::create($year, $fMonth, 1);

            // Get the last date and time of the month
            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
        }
        //dd($pastMonthDate);
        $TDcustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'))
            // ->whereIn(
            //     'ticketmaster.CustomerMobile',
            //     function ($query) use ($datefrom,$endOfMonth) {
            //         $query->select('CustomerMobile')
            //             ->from(with(new TicketMaster)->getTable())
            //             // ->where('strEntryDate', '<', $pastMonthDate)
            //             //->where('strEntryDate', '>=', $datefrom)
            //             ->where('strEntryDate', '<=', $endOfMonth)
            //             ->where('OemCompannyId', Session::get('CompanyId'));
            //     }
            // )
            ->where('strEntryDate', '<=', $endOfMonth)
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('ticketmaster.strEntryDate', [$datefrom, $dateto]));
            $TDcustomer=$TDcustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
            
        $Totalcustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'));
            if ($request->fMonth != "") {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $month = $request->fMonth; // Assuming fMonth is in 'YYYY-MM' format
                    // $date = Carbon::createFromFormat('m', $month); // Parse the month
                    $endDate = Carbon::create($year, $month, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    //$endOfMonth = $date->endOfMonth(); // Get the last day of the month
                    $Totalcustomers->where('strEntryDate', '<=', $endOfMonth);
                }else {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $fMonth = date('m');
                    $endDate = Carbon::create($year, $fMonth, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $Totalcustomers->where('strEntryDate', '<=', $endOfMonth);
                }
            if ($request->yearId != "") {
                $Totalcustomers->where('strEntryDate', '<=', $dateto);
            } else {
                $Totalcustomers->where('strEntryDate', '<=', $dateto);
            } 
            $Totalcustomer=$Totalcustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
        
        $NewCustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'))
            // ->whereIn(
            //     'ticketmaster.CustomerMobile',
            //     function ($query) use ($datefrom,$endOfMonth,$year) {
            //         $query->select('CustomerMobile')
            //             ->from(with(new TicketMaster)->getTable())
            //             ->whereMonth('strEntryDate', date('m',strtotime($endOfMonth)))
            //             ->whereYear('strEntryDate',$year)
            //             ->where('OemCompannyId', Session::get('CompanyId'));
            //     }
            // )
            ->whereMonth('strEntryDate', date('m',strtotime($endOfMonth)))
            ->whereYear('strEntryDate',$year)
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('ticketmaster.strEntryDate', [$datefrom, $dateto]));
            // if ($request->yearId != "") {
            //     $NewCustomers->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
            // } else {
            //     $NewCustomers->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
            // } 
            $NewCustomer = $NewCustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
        
        $where = "1=1";
        if (isset($request->fMonth)) {
            $where .= " and MONTH(ticketmaster.strEntryDate) = '" . $request->fMonth . "'";
        }
        if (isset($request->yearId)) {
            $where .= " and ticketmaster.strEntryDate between '" . $datefrom . "' and '" . $dateto . "'";
        }
        if (isset($CompanyMaster->iCompanyId)) {
            $where .= " and ticketmaster.OemCompannyId='" . $CompanyMaster->iCompanyId . "'";
        }else{
            $where .= " and ticketmaster.OemCompannyId='" .Session::get('CompanyId') . "'";
        }
        
        $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
            (select count(*) as t1 from ticketmaster where
            " . $where . "
            UNION ALL
            select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
             and " . $where . ") tbl");
        $ticketCall = $ticketCallQuery[0]->tCall;
        // $Total_Rma = DB::table('rma')->count();
        // $Total_Rma_Open = DB::table('rma')->where('strStatus', 'Open')->count();
        //  $Total_Rma = DB::table('rma')
        //     ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
        //     ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('created_at', [$datefrom, $dateto]))
        //     ->where('OemCompannyId', Session::get('CompanyId'))
        //     ->count();

        // $Total_Rma_Open = DB::table('rma')->where('strStatus', 'Open')
        //     ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
        //     ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('created_at', [$datefrom, $dateto]))
        //     ->where('OemCompannyId', Session::get('CompanyId'))
        //     ->count();
        $year = 0;
        $fMonth = 0;
        if ($request->fMonth != "") {
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth;
        }else {
            $fMonth = date('m');
            if($fMonth >=4 && $fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
        }
        $Total_Rma = DB::table('rma')
            //->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
            ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('strRMARegistrationDate', $fMonth)->whereYear('strRMARegistrationDate',$year))
            // ->whereMonth('strRMARegistrationDate', $fMonth)
            // ->whereYear('strRMARegistrationDate',$year)
            ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('strRMARegistrationDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();
            
        // echo $fMonth;
        // echo $year;
        // echo $datefrom, $dateto;
        // dd($Total_Rma);
        $Total_Rma_Open = DB::table('rma')->where('strStatus', 'Open')
            ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('strRMARegistrationDate', $fMonth)->whereYear('strRMARegistrationDate',$year))
            // ->whereMonth('strRMARegistrationDate', $fMonth)
            // ->whereYear('strRMARegistrationDate',$year)
            //->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
            ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('strRMARegistrationDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();
        
        $dashboarCount = array('TotalRma_Open' => $Total_Rma_Open, 'Total_Rma' => $Total_Rma,'call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custTDCompany' => $custTDCompany,  'NewCompany' => $NewCompany, 'TDCustomer' => count($TDcustomer), 'NewCustomer' => count($NewCustomer),"totalCustCompany" => $totalCustCompany,"Totalcustomer" => count($Totalcustomer));

        return json_encode($dashboarCount);
    }*/
    
    public function getDashboardCount(Request $request)
    {

        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iUserId' => Auth::user()->id])->first();

        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));

        $totalTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();
        $openTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereIn('finalStatus', array(0, 3))
            ->count();

        /*$custTDCompanies = DB::table('ticketmaster')
            ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
            ->where('OemCompannyId', Session::get('CompanyId'));*/
            $endOfMonth = "";
            $year = 0;
            $fMonth = 0;
            if ($request->fMonth != "") {
                if($request->fMonth >=4 && $request->fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $fMonth = $request->fMonth;
                $endDate = Carbon::create($year, $fMonth, 1);
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                //$custTDCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
            }
            else {
                // if(date('m') >=4 && date('m') <= 12){
                //     $year = date('Y',strtotime($yeardetail->startDate));
                // } else {
                //     $year = date('Y',strtotime($yeardetail->endDate));
                // }
                $endOfMonth = $dateto;
            }
            /*else {
                $fMonth = date('m');
                if($fMonth >=4 && $fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $endDate = Carbon::create($year, $fMonth, 1);
                
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                //$custTDCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
            }*/
        /*$custTDCompanies = DB::table(DB::raw('(SELECT COUNT(*) AS CoumpanyFound, 
                    YEAR(ticketmaster.strEntryDate) AS calcyear, 
                    MONTH(ticketmaster.strEntryDate) AS calcmonth, 
                    ticketmaster.iCompanyId 
                FROM ticketmaster 
                JOIN companyclient ON ticketmaster.iCompanyId = companyclient.iCompanyClientId 
                WHERE companyclient.iCompanyId = '.Session::get('CompanyId').' 
                AND ticketmaster.OemCompannyId = '.Session::get('CompanyId').' 
                AND ticketmaster.strEntryDate BETWEEN "'.$datefrom.'" AND "'.$endOfMonth.'" 
                GROUP BY YEAR(ticketmaster.strEntryDate), MONTH(ticketmaster.strEntryDate), ticketmaster.iCompanyId
            ) as tabl1'));
            if($fMonth > 0){
                $custTDCompanies->where('calcmonth', $fMonth);
            }
            if($year > 0){
                $custTDCompanies->where('calcyear', $year);
            }
            $custTDCompany = $custTDCompanies->count('CoumpanyFound');*/
            $custTDCompanies = DB::table('ticketmaster')
                ->join('companyclient', 'ticketmaster.iCompanyId', '=', 'companyclient.iCompanyClientId')
                ->where('companyclient.iCompanyId', Session::get('CompanyId'))
                ->where('ticketmaster.OemCompannyId', Session::get('CompanyId'))
                ->whereBetween('ticketmaster.strEntryDate', [$datefrom, $endOfMonth]);
                if($fMonth > 0){
                    $custTDCompanies->whereMonth('ticketmaster.strEntryDate', $fMonth);
                }
                if($year > 0){
                    $custTDCompanies->whereYear('ticketmaster.strEntryDate', $year);
                }
            $custTDCompany = $custTDCompanies->distinct()
                ->count('ticketmaster.iCompanyId');

            
            
        /*$custTDCompanies->whereNotIn(
            'ticketmaster.iCompanyId',
            function ($query) use ($datefrom) {
                $query->select('ticketmaster.iCompanyId')
                    ->from(with(new TicketMaster)->getTable())
                    ->where('strEntryDate', '<', $datefrom)
                    ->where('OemCompannyId', Session::get('CompanyId'));
            }
        );*/
            
        /*$custTDCompany = $custTDCompanies->distinct()
            ->count('ticketmaster.iCompanyId');*/
        
        $totalCustCompanies = DB::table('ticketmaster')
            ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
            ->where('OemCompannyId', Session::get('CompanyId'));
            if ($request->fMonth != "") {
                $year = 0;
                if($request->fMonth >=4 && $request->fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $month = $request->fMonth;
                $endDate = Carbon::create($year, $month, 1);
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                $totalCustCompanies->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
            } 
            if ($request->yearId != "") {
                $totalCustCompanies->where('ticketmaster.strEntryDate', '<=', $dateto);
            } 
            else {
                $totalCustCompanies->where('ticketmaster.strEntryDate', '<=', $dateto);
            }
        $totalCustCompany = $totalCustCompanies->distinct()
            ->count('ticketmaster.iCompanyId');
        
        $NewCompanies = DB::table('ticketmaster')
            ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
            ->where('OemCompannyId', Session::get('CompanyId'));
            if ($request->fMonth != "") {
                $year = 0;
                if($request->fMonth >=4 && $request->fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $fMonth = $request->fMonth;
                $endDate = Carbon::create($year, $fMonth, 1);
                $end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                $NewCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                ->whereNotIn(
                    'ticketmaster.iCompanyId',
                    function ($query) use ($end_Of_Month) {
                        $query->select('ticketmaster.iCompanyId')
                            ->from(with(new TicketMaster)->getTable())
                            ->where('strEntryDate', '<', $end_Of_Month)
                            ->where('OemCompannyId', Session::get('CompanyId'));
                    }
                );
            } else {
                $year = 0;
                $fMonth = date('m');
                if($fMonth >=4 && $fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $endDate = Carbon::create($year, $fMonth, 1);
                
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                //$NewCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                $NewCompanies->whereNotIn(
                    'ticketmaster.iCompanyId',
                    function ($query) use ($datefrom) {
                        $query->select('ticketmaster.iCompanyId')
                            ->from(with(new TicketMaster)->getTable())
                            ->where('strEntryDate', '<', $datefrom)
                            ->where('OemCompannyId', Session::get('CompanyId'));
                    }
                );
            }
        
        $NewCompany = $NewCompanies->distinct()
            ->count('ticketmaster.iCompanyId');
        // $NewCompany = $NewCompanies->toSql();
        // echo Session::get('CompanyId');
        // echo $request->fMonth;
        // echo "<br />";
        // echo $datefrom;
        // echo "<br />";
        // echo $dateto;
        // dd($NewCompany);
            
        // if($request->fMonth == null || $request->fMonth == ""){
        //     $pastMonthDate = date('Y-m-01');
        // } else {
        //     $pastMonthDate = date('Y-' . $request->fMonth . '-01');
        // }
        /*$endOfMonth = "";
        $year = 0;
        if($request->fMonth == null || $request->fMonth == ""){
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth ?? date('m');
            $endDate = Carbon::create($year, $fMonth, 1);

            // Get the last date and time of the month
            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
        } else {
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth ?? date('m');
            $endDate = Carbon::create($year, $fMonth, 1);

            // Get the last date and time of the month
            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
        }
        //dd($pastMonthDate);
        $TDcustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'))
            // ->whereIn(
            //     'ticketmaster.CustomerMobile',
            //     function ($query) use ($datefrom,$endOfMonth) {
            //         $query->select('CustomerMobile')
            //             ->from(with(new TicketMaster)->getTable())
            //             // ->where('strEntryDate', '<', $pastMonthDate)
            //             //->where('strEntryDate', '>=', $datefrom)
            //             ->where('strEntryDate', '<=', $endOfMonth)
            //             ->where('OemCompannyId', Session::get('CompanyId'));
            //     }
            // )
            ->where('strEntryDate', '<=', $endOfMonth)
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('ticketmaster.strEntryDate', [$datefrom, $dateto]));
            $TDcustomer=$TDcustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
            
        $Totalcustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'));
            if ($request->fMonth != "") {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $month = $request->fMonth; // Assuming fMonth is in 'YYYY-MM' format
                    // $date = Carbon::createFromFormat('m', $month); // Parse the month
                    $endDate = Carbon::create($year, $month, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    //$endOfMonth = $date->endOfMonth(); // Get the last day of the month
                    $Totalcustomers->where('strEntryDate', '<=', $endOfMonth);
                }else {
                    $year = 0;
                    if($request->fMonth >=4 && $request->fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $fMonth = date('m');
                    $endDate = Carbon::create($year, $fMonth, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $Totalcustomers->where('strEntryDate', '<=', $endOfMonth);
                }
            if ($request->yearId != "") {
                $Totalcustomers->where('strEntryDate', '<=', $dateto);
            } else {
                $Totalcustomers->where('strEntryDate', '<=', $dateto);
            } 
            $Totalcustomer=$Totalcustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
        
        $NewCustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'))
            // ->whereIn(
            //     'ticketmaster.CustomerMobile',
            //     function ($query) use ($datefrom,$endOfMonth,$year) {
            //         $query->select('CustomerMobile')
            //             ->from(with(new TicketMaster)->getTable())
            //             ->whereMonth('strEntryDate', date('m',strtotime($endOfMonth)))
            //             ->whereYear('strEntryDate',$year)
            //             ->where('OemCompannyId', Session::get('CompanyId'));
            //     }
            // )
            ->whereMonth('strEntryDate', date('m',strtotime($endOfMonth)))
            ->whereYear('strEntryDate',$year)
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('ticketmaster.strEntryDate', [$datefrom, $dateto]));
            // if ($request->yearId != "") {
            //     $NewCustomers->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
            // } else {
            //     $NewCustomers->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto);
            // } 
            $NewCustomer = $NewCustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();*/
            
            $TDcustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'));
            /*if ($request->fMonth != "") {
                $year = 0;
                if($request->fMonth >=4 && $request->fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $fMonth = $request->fMonth;
                $endDate = Carbon::create($year, $fMonth, 1);
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                $TDcustomers->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
            } else {
                $year = 0;
                $fMonth = date('m');
                if($fMonth >=4 && $fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $endDate = Carbon::create($year, $fMonth, 1);
                
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                $TDcustomers->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
            }*/
        
        /*$TDcustomers->whereNotIn(
            'ticketmaster.CustomerMobile',
            function ($query) use ($datefrom) {
                $query->select('ticketmaster.CustomerMobile')
                    ->from(with(new TicketMaster)->getTable())
                    ->where('strEntryDate', '<', $datefrom)
                    ->where('OemCompannyId', Session::get('CompanyId'));
            }
        );*/
        $endOfMonth = "";
        $year = 0;
        $fMonth = 0;
        if ($request->fMonth != "") {
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth;
            $endDate = Carbon::create($year, $fMonth, 1);
            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
            //$custTDCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
        }
        else {
            $endOfMonth = $dateto;
        }
        
        $TDcustomers->whereBetween('ticketmaster.strEntryDate', [$datefrom, $endOfMonth]);
        if($fMonth > 0){
            $TDcustomers->whereMonth('ticketmaster.strEntryDate', $fMonth);
        }
        if($year > 0){
            $TDcustomers->whereYear('ticketmaster.strEntryDate', $year);
        }
        $TDcustomer = $TDcustomers->distinct()
                ->count('CustomerMobile');
            
        // $TDcustomer=$TDcustomers->groupBy('ticketmaster.CustomerMobile')
        //     ->get();
            
        $Totalcustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'));
            if ($request->fMonth != "") {
                $year = 0;
                if($request->fMonth >=4 && $request->fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $month = $request->fMonth;
                $endDate = Carbon::create($year, $month, 1);
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                $Totalcustomers->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
            } 
            if ($request->yearId != "") {
                $Totalcustomers->where('ticketmaster.strEntryDate', '<=', $dateto);
            } 
            else {
                $Totalcustomers->where('ticketmaster.strEntryDate', '<=', $dateto);
            }
            $Totalcustomer=$Totalcustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
        
        $NewCustomers = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'));
            if ($request->fMonth != "") {
                $year = 0;
                if($request->fMonth >=4 && $request->fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $fMonth = $request->fMonth;
                $endDate = Carbon::create($year, $fMonth, 1);
                $end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                $NewCustomers->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
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
                $year = 0;
                $fMonth = date('m');
                if($fMonth >=4 && $fMonth <= 12){
                    $year = date('Y',strtotime($yeardetail->startDate));
                } else {
                    $year = date('Y',strtotime($yeardetail->endDate));
                }
                $endDate = Carbon::create($year, $fMonth, 1);
                
                $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                //$NewCustomers->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                $NewCustomers->whereNotIn(
                    'ticketmaster.CustomerMobile',
                    function ($query) use ($datefrom) {
                        $query->select('ticketmaster.CustomerMobile')
                            ->from(with(new TicketMaster)->getTable())
                            ->where('strEntryDate', '<', $datefrom)
                            ->where('OemCompannyId', Session::get('CompanyId'));
                    }
                );
            } 
            $NewCustomer = $NewCustomers->groupBy('ticketmaster.CustomerMobile')
            ->get();
        
        $where = "1=1";
        if (isset($request->fMonth)) {
            $where .= " and MONTH(ticketmaster.strEntryDate) = '" . $request->fMonth . "'";
        }
        if (isset($request->yearId)) {
            $where .= " and ticketmaster.strEntryDate between '" . $datefrom . "' and '" . $dateto . "'";
        }
        if (isset($CompanyMaster->iCompanyId)) {
            $where .= " and ticketmaster.OemCompannyId='" . $CompanyMaster->iCompanyId . "'";
        }else{
            $where .= " and ticketmaster.OemCompannyId='" .Session::get('CompanyId') . "'";
        }
        
        $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
            (select count(*) as t1 from ticketmaster where
            " . $where . "
            UNION ALL
            select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
             and " . $where . ") tbl");
        $ticketCall = $ticketCallQuery[0]->tCall;
        // $Total_Rma = DB::table('rma')->count();
        // $Total_Rma_Open = DB::table('rma')->where('strStatus', 'Open')->count();
         /*$Total_Rma = DB::table('rma')
            ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
            ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('created_at', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();

        $Total_Rma_Open = DB::table('rma')->where('strStatus', 'Open')
            ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
            ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('created_at', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();*/
        $year = 0;
        $fMonth = 0;
        if ($request->fMonth != "") {
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth;
        }else {
            $fMonth = date('m');
            if($fMonth >=4 && $fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
        }
        $Total_Rma = DB::table('rma')
            //->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
            ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('strRMARegistrationDate', $fMonth)->whereYear('strRMARegistrationDate',$year))
            // ->whereMonth('strRMARegistrationDate', $fMonth)
            // ->whereYear('strRMARegistrationDate',$year)
            ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('strRMARegistrationDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();
            
        // echo $fMonth;
        // echo $year;
        // echo $datefrom, $dateto;
        // dd($Total_Rma);
        $Total_Rma_Open = DB::table('rma')->where('strStatus', 'Open')
            ->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('strRMARegistrationDate', $fMonth)->whereYear('strRMARegistrationDate',$year))
            // ->whereMonth('strRMARegistrationDate', $fMonth)
            // ->whereYear('strRMARegistrationDate',$year)
            //->when($request->fMonth, fn($query, $fMonth) => $query->whereMonth('created_at', $fMonth))
            ->when($request->yearId, fn($query, $yearId) => $query->whereBetween('strRMARegistrationDate', [$datefrom, $dateto]))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->count();
        
        $dashboarCount = array('TotalRma_Open' => $Total_Rma_Open, 'Total_Rma' => $Total_Rma,'call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custTDCompany' => $custTDCompany,  'NewCompany' => $NewCompany, 'TDCustomer' => $TDcustomer, 'NewCustomer' => count($NewCustomer),"totalCustCompany" => $totalCustCompany,"Totalcustomer" => count($Totalcustomer));

        return json_encode($dashboarCount);
    }

    /*public function getAnalyticsLineChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();

        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));

        $calllevel = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(0,3))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();


        $totalSum = DB::table('ticketmaster')->select(
                DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw("count(*) as `total`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->groupBy('month-year')
            ->orderBy('strEntryDate', 'ASC')->get();

        $montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $monthvisetotalarray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentagearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $month = '';
        foreach ($calllevel as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $montharray[$call->month] = $call->total;
            }
        }
        foreach ($totalSum as $sum) {
            if (array_key_exists($sum->month, $monthvisetotalarray)) {
                $monthvisetotalarray[$sum->month] = $sum->total;
            }
        }
        foreach ($monthvisetotalarray as $key => $val) {
            if (array_key_exists($key, $montharray)) {
                $percentagearray[$key] = $val == 0 ? 0 : ($montharray[$key] * 100) / $val;
            }
        }

        $dataArr = array();
        foreach ($percentagearray as $key => $val) {
            $dataArr[] = array(
                "Count" => $val,
                "key" => $key
            );
        }
        $resolvedbyl1level = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();

        $Resolvedbyl1array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentageResolvedbyl1array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        foreach ($resolvedbyl1level as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $Resolvedbyl1array[$call->month] = $call->total;
            }
        }
        foreach ($monthvisetotalarray as $key => $val) {
            if (array_key_exists($key, $Resolvedbyl1array)) {
                $percentageResolvedbyl1array[$key] = $val == 0 ? 0 : ($Resolvedbyl1array[$key] * 100) / $val;
            }
        }

        $resolvedbyl2level = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '2')
            //->where('iLevel2CallAttendentId', '0')
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();

        $Resolvedbyl2array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentageResolvedbyl2array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        foreach ($resolvedbyl2level as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $Resolvedbyl2array[$call->month] = $call->total;
            }
        }
        foreach ($monthvisetotalarray as $key => $val) {
            if (array_key_exists($key, $Resolvedbyl2array)) {
                $percentageResolvedbyl2array[$key] = $val == 0 ? 0 : ($Resolvedbyl2array[$key] * 100) / $val;
            }
        }

        $allDataArr = array(
            "levelOne" => $dataArr,
            "resolvedbyl1" => $percentageResolvedbyl1array,
            "resolvedbyl2" => $percentageResolvedbyl2array,
        );

        return json_encode($allDataArr);
    } */

    public function getAnalyticsLineChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();

        $datefrom = date('Y-m-d 00:00:00', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));

        $calllevel = DB::table('ticketmaster')
            ->select(
                DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`")
            )
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('iLevel2CallAttendentId', '0')
            ->where('LevelId', '1')
            //->whereIn('finalStatus', array(0,3))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();


        // $totalSum = DB::table('ticketmaster')->select(
        //         DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
        //         DB::raw("count(*) as `total`"),
        //         DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"))
        //     ->where('OemCompannyId', Session::get('CompanyId'))
        //     ->whereBetween('strEntryDate', [$datefrom, $dateto])
        //     ->groupBy('month-year')
        //     ->orderBy('strEntryDate', 'ASC')->get();

        $montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $monthvisetotalarray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentagearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $month = '';
        foreach ($calllevel as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $montharray[$call->month] = $call->total;
            }
        }
        // foreach ($totalSum as $sum) {
        //     if (array_key_exists($sum->month, $monthvisetotalarray)) {
        //         $monthvisetotalarray[$sum->month] = $sum->total;
        //     }
        // }
        // foreach ($monthvisetotalarray as $key => $val) {
        //     if (array_key_exists($key, $montharray)) {
        //         $percentagearray[$key] = $val == 0 ? 0 : ($montharray[$key] * 100) / $val;
        //     }
        // }

        $dataArr = array();
        foreach ($montharray as $key => $val) {
            $dataArr[] = array(
                "Count" => $val,
                "key" => $key
            );
        }

        $resolvedbyl1level = DB::table('ticketmaster')
            ->select(
                DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`")
            )
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '0')
            // ->where(function ($query)  use ($datefrom, $dateto){
            //     $query->where('LevelId', 1)
            //         ->orWhere(function ($subQuery)  use ($datefrom, $dateto) {
            //             $subQuery->where('LevelId', 1)
            //                 ->whereExists(function ($exists)  use ($datefrom, $dateto) {
            //                     $exists->select(DB::raw(1))
            //                         ->from('ticketlog')
            //                         ->whereRaw('ticketlog.iticketId = ticketmaster.iTicketId')
            //                         ->whereBetween('strEntryDate', [$datefrom, $dateto])
            //                         ->whereIn('iStatus', [1, 4, 5])
            //                         ->where('ticketlog.LevelId', 1);
            //                 });
            //         });
            // })
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();

        $Resolvedbyl1array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentageResolvedbyl1array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        foreach ($resolvedbyl1level as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $Resolvedbyl1array[$call->month] = $call->total;
            }
        }
        // foreach ($monthvisetotalarray as $key => $val) {
        //     if (array_key_exists($key, $Resolvedbyl1array)) {
        //         $percentageResolvedbyl1array[$key] = $val == 0 ? 0 : ($Resolvedbyl1array[$key] * 100) / $val;
        //     }
        // }

        /*$resolvedbyl2level = DB::table('ticketmaster')
            ->select(
                DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`")
            )
            ->where('OemCompannyId', Session::get('CompanyId'))
            //->where('LevelId', '1')
            //->where('iLevel2CallAttendentId', '!=', '0')
            ->where(function ($query)  use ($datefrom, $dateto){
                $query->where('LevelId', 2)
                    ->orWhere(function ($subQuery)  use ($datefrom, $dateto) {
                        $subQuery->where('LevelId', 1)
                            ->whereExists(function ($exists)  use ($datefrom, $dateto) {
                                $exists->select(DB::raw(1))
                                    ->from('ticketlog')
                                    ->whereRaw('ticketlog.iticketId = ticketmaster.iTicketId')
                                    ->whereBetween('strEntryDate', [$datefrom, $dateto])
                                    ->whereIn('iStatus', [1, 4, 5])
                                    ->where('ticketlog.LevelId', 2);
                            });
                    });
            })
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();*/
            $resolvedbyl2level = DB::table('ticketmaster')
                ->select(
                    DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                    DB::raw('count(*) as `total`'),
                    DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                    DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`")
                )
                ->where('OemCompannyId', Session::get('CompanyId'))
                ->where(function($query) use ($datefrom, $dateto) {
                    $query->where('LevelId', 2)
                          ->orWhereIn('LevelId', function($subQuery) use ($datefrom, $dateto) {
                              $subQuery->select('ticketlog.LevelId')
                                       ->from('ticketlog')
                                       ->whereColumn('ticketlog.iticketId', 'ticketmaster.iTicketId')
                                       ->whereBetween('strEntryDate',[$datefrom, $dateto])
                                       ->where('LevelId', 2);
                          });
                })
                ->whereBetween('strEntryDate', [$datefrom, $dateto])
                ->groupBy('month-year')
                ->orderBy('strEntryDate', 'asc')
                ->get();
            // ->toSql();
            // echo  Session::get('CompanyId');
            // echo "<br />";
            // echo $datefrom."<br />". $dateto;
            // echo "<br />";
            // dd($resolvedbyl2level);

        $Resolvedbyl2array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentageResolvedbyl2array = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        
        foreach ($resolvedbyl2level as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $Resolvedbyl2array[$call->month] = $call->total;
            }
        }
        // foreach ($monthvisetotalarray as $key => $val) {
        //     if (array_key_exists($key, $Resolvedbyl2array)) {
        //         $percentageResolvedbyl2array[$key] = $val == 0 ? 0 : ($Resolvedbyl2array[$key] * 100) / $val;
        //     }
        // }

        $allDataArr = array(
            "levelOne" => $dataArr,
            // "resolvedbyl1" => $percentageResolvedbyl1array,
            // "resolvedbyl2" => $percentageResolvedbyl2array,
            "resolvedbyl1" => $Resolvedbyl1array,
            "resolvedbyl2" => $Resolvedbyl2array,
        );

        return json_encode($allDataArr);
    }


    public function selectswitchuser(Request $request)
    {
        $CompanyMaster = DB::table('multiplecompanyrole')->select(
            'multiplecompanyrole.*',
            'companymaster.strOEMCompanyName',
            'roles.name',
        )
            ->where(['multiplecompanyrole.isDelete' => 0, 'multiplecompanyrole.iStatus' => 1, 'multiplecompanyrole.userid' => Auth::user()->id])
            ->join('companymaster', 'companymaster.iCompanyId', '=', 'multiplecompanyrole.iOEMCompany')
            ->join('roles', 'roles.id', '=', 'multiplecompanyrole.iRoleId')
            ->get();
        return view('call_attendant.afterlogin', compact('CompanyMaster'));
    }


    //08-05-2024
    // wlAdmin to front Switch
    public function switchuser(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->role_id = 3;
        $user->save();
        // dd($request);
        // dd(Auth::user()->role_id);
        $GetCompanyFromFront = session('CompanyId');
        // dd($GetCompanyFromFront);
        $user = User::find(Auth::user()->id);
        $menuArray = array();
        if ($user->role_id == 2) {
            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iUserId' => Auth::user()->id])->first();
            if (!$CompanyMaster) {
                $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
                // dd($companyUser);
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
                // dd($CompanyMaster);

                $request->session()->put('iRoleId', $companyUser->iRoleId);
                $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                    ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();

                foreach ($MenuList as $menu) {
                    $menuArray[] = $menu->permission_id;
                }
                $request->session()->put('menuList', $menuArray);
            } else {
                $MenuList = DB::table('permissions')->get();

                foreach ($MenuList as $menu) {
                    $menuArray[] = $menu->id;
                }
                $request->session()->put('menuList', $menuArray);
                $request->session()->put('iRoleId', '0');
            }
            // dd('Hello');

            $request->session()->put('CompanyName', $CompanyMaster->strOEMCompanyName);
            $request->session()->put('CompanyId', $CompanyMaster->iCompanyId);
            $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
            $totalTicket = DB::table('ticketmaster')
                ->where('OemCompannyId', $CompanyMaster->iCompanyId)
                ->count();
            $openTicket = DB::table('ticketmaster')
                ->where(['OemCompannyId' => $CompanyMaster->iCompanyId, 'finalStatus' => '0'])
                ->count();
            $custCompany = DB::table('companyclient')
                ->where('iCompanyId', $CompanyMaster->iCompanyId)->where('iStatus', '1')
                ->where('isDelete', '0')
                ->count();

            $customer = DB::table('ticketmaster')
                ->where('ticketmaster.OemCompannyId', $CompanyMaster->iCompanyId)
                ->groupBy('ticketmaster.CustomerMobile')
                ->get();




            $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
                (
                select count(*) as t1 from ticketmaster where
                ticketmaster.OemCompannyId = " . $CompanyMaster->iCompanyId . "
                UNION ALL
                select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
                and ticketmaster.OemCompannyId = " . $CompanyMaster->iCompanyId . ") tbl");
            $ticketCall = $ticketCallQuery[0]->tCall;
            $dashboarCount = array('call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custCompany' => $custCompany, 'customer' => count($customer));
            $companyInfo = CompanyInfo::select('headerColor', 'menuColor', 'menubgColor', 'strLogo')->where('iCompanyId', $CompanyMaster->iCompanyId)->first();

            if (isset($companyInfo->strLogo) && $companyInfo->strLogo != "") {
                $request->session()->put('CompanyLogo', env('APP_URL') . '/WlLogo/' . $companyInfo->strLogo);
            } else {
                $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo.png");
            }
            $this->changeCssColor($request, $companyInfo);

            return view('wladmin.index', compact('yearList', 'dashboarCount'));
        } else if ($user->role_id == 3) {
            // dd(Auth::user()->role_id);
            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])->get();

            $companyInfo = CompanyInfo::select('headerColor', 'menuColor', 'menubgColor', 'strLogo')->where('iCompanyId', session('CompanyId'))->first();

            if (isset($companyInfo->strLogo) && $companyInfo->strLogo != "") {
                $request->session()->put('CompanyLogo', env('APP_URL') . '/WlLogo/' . $companyInfo->strLogo);
            } else {
                $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
            }
            // dd($CompanyMaster);
            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();


            $callAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                ->first();
            if ($callAttendent) {
                $request->session()->put('exeLevel', $callAttendent->iExecutiveLevel);
            } else {
                $request->session()->put('exeLevel', 2);
            }
            return view('call_attendant.home', compact('CompanyMaster', 'statemasters', 'citymasters'));
        } else {

            Auth::logout();
            Session::forget('user');
            return redirect()->route('login');
        }
    }

    // front to wlAdmin Switch
    public function switchusersubmit(Request $request)
    {
        // dd($request);
        $user = User::find(Auth::user()->id);
        $user->role_id = 2;
        $user->save();
        if ($request->iOEMCompany) {
            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iOEMCompany])->first();
            $request->session()->put('CompanyName', $CompanyMaster->strOEMCompanyName);
            $request->session()->put('CompanyId', $CompanyMaster->iCompanyId);

            return redirect()->route('wladmin.dashboard');
        } else {
            if (session()->has('CompanyId')) {
                $GetCompanyFromFront = session('CompanyId');
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $GetCompanyFromFront])->first();
                return redirect()->route('wladmin.dashboard');
            } else {
                return redirect()->route('home')->with('error', 'Session expired. Please log in again.');
            }
        }
        // $GetCompanyFromFront = session('GetCompanyFromFront');

        // $userid = Auth::user()->id;

        // $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $GetCompanyFromFront])->first();
        // $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iOEMCompany])->first();



        // $user = User::find(Auth::user()->id);
        // $user->role_id = 2;
        // $user->save();

        // if (!$CompanyMaster) {
        //     $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
        //     $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
        //     $request->session()->put('iRoleId', $companyUser->iRoleId);
        //     $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
        //         ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
        //     $menuArray = array();
        //     foreach ($MenuList as $menu) {
        //         $menuArray[] = $menu->permission_id;
        //     }
        //     $request->session()->put('menuList', $menuArray);
        // } else {
        //     if ($request->iRoleId != "" || $request->iRoleId != null) {
        //         $MenuList = DB::table('role_has_permissions')->where(['role_id' => $request->iRoleId])
        //             ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
        //         $menuArray = array();
        //     } else {
        //         $MenuList = DB::table('permissions')->get();
        //         $menuArray = array();
        //     }

        //     foreach ($MenuList as $menu) {
        //         $menuArray[] = $menu->id;
        //     }
        //     $request->session()->put('menuList', $menuArray);
        //     $request->session()->put('iUserId', $userid);
        //     // $request->session()->put('iRoleId', $userid);
        //     $request->session()->put('GetCompanyFromFront', $request->iOEMCompany);
        //     $request->session()->put('SwitchRole', "2");
        //     $request->session()->put('Switch', "0");

        //     // Session::put('GetCompanyFromFront', $company);
        // }


        // $request->session()->put('CompanyName', $CompanyMaster->strOEMCompanyName);
        // $request->session()->put('CompanyId', $CompanyMaster->iCompanyId);
        // $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
        // $totalTicket = DB::table('ticketmaster')
        //     ->where('OemCompannyId', $CompanyMaster->iCompanyId)
        //     ->count();
        // $openTicket = DB::table('ticketmaster')
        //     ->where(['OemCompannyId' => $CompanyMaster->iCompanyId, 'finalStatus' => '0'])
        //     ->count();
        // $custCompany = DB::table('companyclient')
        //     ->where('iCompanyId', $CompanyMaster->iCompanyId)->where('iStatus', '1')
        //     ->where('isDelete', '0')
        //     ->count();

        // $customer = DB::table('ticketmaster')
        //     ->where('ticketmaster.OemCompannyId', $CompanyMaster->iCompanyId)
        //     ->groupBy('ticketmaster.CustomerMobile')
        //     ->get();




        // $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
        //         (
        //         select count(*) as t1 from ticketmaster where
        //         ticketmaster.OemCompannyId = " . $CompanyMaster->iCompanyId . "
        //         UNION ALL
        //         select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
        //         and ticketmaster.OemCompannyId = " . $CompanyMaster->iCompanyId . ") tbl");
        // $ticketCall = $ticketCallQuery[0]->tCall;
        // $dashboarCount = array('call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custCompany' => $custCompany, 'customer' => count($customer));
        // $companyInfo = CompanyInfo::select('headerColor', 'menuColor', 'menubgColor', 'strLogo')->where('iCompanyId', $CompanyMaster->iCompanyId)->first();

        // if (isset($companyInfo->strLogo) && $companyInfo->strLogo != "") {
        //     $request->session()->put('CompanyLogo', env('APP_URL') . '/WlLogo/' . $companyInfo->strLogo);
        // } else {
        //     $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
        // }
        // $this->changeCssColor($request, $companyInfo);

        // return view('wladmin.index', compact('yearList', 'dashboarCount'));
    }
     public function get_rma_PiaChart(Request $request)
    {

        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
        
        $year = 0;
        $fMonth = 0;
        if ($request->fMonth != "") {
            if($request->fMonth >=4 && $request->fMonth <= 12){
                $year = date('Y',strtotime($yeardetail->startDate));
            } else {
                $year = date('Y',strtotime($yeardetail->endDate));
            }
            $fMonth = $request->fMonth;
        }
        // else {
        //     $fMonth = date('m');
        //     if($fMonth >=4 && $fMonth <= 12){
        //         $year = date('Y',strtotime($yeardetail->startDate));
        //     } else {
        //         $year = date('Y',strtotime($yeardetail->endDate));
        //     }
        // }
        
        $callLog1 = DB::table('rma')
            ->leftJoin('system', 'rma.strSelectSystem', '=', 'system.iSystemId')
            ->select('system.strSystem as SystemName', DB::raw('COUNT(*) as Count'))
            ->whereBetween('rma.strRMARegistrationDate', [$datefrom, $dateto])
            // ->when($request->fMonth, fn($query, $fMonth)
            //     => $query->whereMonth('rma.created_at', '=', $fMonth))
            ->when($fMonth, fn($query, $fMonth)
                => $query->whereMonth('rma.strRMARegistrationDate', '=', $fMonth))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->groupBy('system.strSystem')
            ->get();

        return json_encode($callLog1);
    }
    // public function get_rmaLineChart(Request $request)
    // {
       
    //     $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
    //     $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
    //     $dateto = date('Y-m-d', strtotime($yeardetail->endDate));

    //     $calllevel = DB::table('rma')
    //         ->select(
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%Y-%m') as `month-year`"),
    //             DB::raw('count(*) as `total`'),
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%b') as `month`"),
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%c') as `monthNo`")
    //         )
    //         ->whereBetween('strRMARegistrationDate', [$datefrom, $dateto])
    //         ->orderBy('strRMARegistrationDate', 'ASC')
    //         ->where('OemCompannyId', Session::get('CompanyId'))
    //         ->groupBy('month-year')
    //         ->get();


    //     $totalSum = DB::table('rma')
    //         ->select(
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%Y-%m') as `month-year`"),
    //             DB::raw("count(*) as `total`"),
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%b') as `month`")
    //         )
    //         ->whereBetween('strRMARegistrationDate', [$datefrom, $dateto])
    //         ->where('OemCompannyId', Session::get('CompanyId'))
    //         ->groupBy('month-year')
    //         ->orderBy('strRMARegistrationDate', 'ASC')->get();

    //     $montharray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     $monthvisetotalarray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     $percentagearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     $month = '';
    //     foreach ($calllevel as $call) {
    //         if (array_key_exists($call->month, $montharray)) {
    //             $montharray[$call->month] = $call->total;
    //         }
    //     }
    //     foreach ($totalSum as $sum) {
    //         if (array_key_exists($sum->month, $monthvisetotalarray)) {
    //             $monthvisetotalarray[$sum->month] = $sum->total;
    //         }
    //     }
    //     foreach ($monthvisetotalarray as $key => $val) {
    //         if (array_key_exists($key, $montharray)) {

    //             $percentagearray[$key] = $val == 0 ? 0 : ($montharray[$key] * 100) / $val;
    //         }
    //     }
    //     $dataArr = array();
    //     foreach ($percentagearray as $key => $val) {
    //         $dataArr[] = array(
    //             "Count" => $val,
    //             "key" => $key
    //         );
    //     }

    //     $callSameDayResolve = DB::table('rma')
    //         ->select(
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%Y-%m') as `month-year`"),
    //             DB::raw('count(*) as `total`'),
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%b') as `month`"),
    //             DB::raw("DATE_FORMAT(strRMARegistrationDate,'%c') as `monthNo`")
    //         )
    //         ->where('rma.strStatus')
    //         ->whereBetween('strRMARegistrationDate', [$datefrom, $dateto])
    //         ->orderBy('strRMARegistrationDate', 'ASC')
    //         ->where('OemCompannyId', Session::get('CompanyId'))
    //         ->groupBy('month-year')
    //         ->get();
                

    //     $monthSameDayResolvearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     $percentageSameDayResolvearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
    //     foreach ($callSameDayResolve as $call) {
    //         if (array_key_exists($call->month, $montharray)) {
    //             $monthSameDayResolvearray[$call->month] = $call->total;
    //         }
    //     }
    //     foreach ($monthvisetotalarray as $key => $val) {
    //         if (array_key_exists($key, $monthSameDayResolvearray)) {
    //             $percentageSameDayResolvearray[$key] = $val == 0 ? 0 : ($monthSameDayResolvearray[$key] * 100) / $val;
    //         }
    //     }

    //     $allDataArr = array(
    //         "levelOne" => $dataArr,
    //         "samedaysolution" => $percentageSameDayResolvearray,
    //     );
       
    //     return json_encode($allDataArr);
    // }
    
     public function get_rmaLineChart(Request $request)
    {
        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d', strtotime($yeardetail->endDate));

        // Fetch RMA count grouped by month
        $calllevel = DB::table('rma')
            ->select(
                DB::raw("DATE_FORMAT(strRMARegistrationDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strRMARegistrationDate,'%b') as `month`")
            )
            ->whereBetween('strRMARegistrationDate', [$datefrom, $dateto])
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->groupBy('month-year')
            ->orderBy('strRMARegistrationDate', 'ASC')
            ->get();

        // Initialize month-wise arrays with 0
        $montharray = array_fill_keys(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'], 0);

        // Assign RMA counts to respective months
        foreach ($calllevel as $call) {
            if (array_key_exists($call->month, $montharray)) {
                $montharray[$call->month] = $call->total;
            }
        }

        // Format data for chart
        $dataArr = [];
        foreach ($montharray as $key => $val) {
            $dataArr[] = [
                "Count" => $val,
                "key" => $key
            ];
        }

        // Fetch same-day resolved RMA count
        $callSameDayResolve = DB::table('rma')
            ->select(
                DB::raw("DATE_FORMAT(strRMARegistrationDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strRMARegistrationDate,'%b') as `month`")
            )
            ->where('rma.strStatus', 'Closed') // Assuming you meant to filter resolved RMAs
            ->whereBetween('strRMARegistrationDate', [$datefrom, $dateto])
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->groupBy('month-year')
            ->orderBy('strRMARegistrationDate', 'ASC')
            ->get();

        // Initialize same-day resolution array
        $monthSameDayResolvearray = array_fill_keys(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'], 0);

        // Assign resolved RMA counts to respective months
        foreach ($callSameDayResolve as $call) {
            if (array_key_exists($call->month, $monthSameDayResolvearray)) {
                $monthSameDayResolvearray[$call->month] = $call->total;
            }
        }

        // Prepare response data
        $allDataArr = [
            "levelOne" => $dataArr,
            "samedaysolution" => $monthSameDayResolvearray, // Now returning count instead of %
        ];

        return response()->json($allDataArr);
    }
    
    public function switchWLuser(Request $request, $company, $userid)
    {
        \Auth::logout($userid);
        $user = User::find($userid);
        \Auth::loginUsingId($user['id']);
        $user->role_id = 2;
        $user->save();
        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $company])->first();
        $request->session()->put('CompanyName', $CompanyMaster->strOEMCompanyName);
        $request->session()->put('CompanyId', $CompanyMaster->iCompanyId);
        $request->session()->put('iUserId', $userid);
        $request->session()->put('iRoleId', '0');
        $request->session()->put('GetCompanyFromFront', $company);
        $request->session()->put('SwitchRole', "2");
        $request->session()->put('Switch', "0");
        $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
        return redirect()->route('wladmin.dashboard');
    }
    
    public function switchWLsubmit(Request $request)
    {
        $userid = Auth::user()->id;
        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iOEMCompany])->first();
        
        $request->session()->put('CompanyName', $CompanyMaster->strOEMCompanyName);
        $request->session()->put('CompanyId', $CompanyMaster->iCompanyId);
        $request->session()->put('iUserId', $userid);
        $request->session()->put('iRoleId', '2');
        $request->session()->put('GetCompanyFromFront', $request->iOEMCompany);
        $request->session()->put('SwitchRole', "2");
        $request->session()->put('Switch', "0");
        $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
        
        return redirect()->route('wladmin.dashboard');
    }
}
