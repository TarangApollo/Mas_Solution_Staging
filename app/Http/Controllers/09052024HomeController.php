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
use App\Models\TicketMaster;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'ipcheck']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $session = Auth::user()->role_id;

        $userId = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        $userdata = User::whereId($userId)->first();

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
            $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iUserId' => Auth::user()->id])->first();
            if (!$CompanyMaster) {
                $companyUser = WlUser::where(['isDelete' => 0, 'iUserId' => Auth::user()->id])->first();
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $companyUser->iCompanyId])->first();
                $request->session()->put('iRoleId', $companyUser->iRoleId);
                $MenuList = DB::table('role_has_permissions')->where(['role_id' => Session::get('iRoleId')])
                    ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')->get();
                $menuArray = array();
                foreach ($MenuList as $menu) {
                    $menuArray[] = $menu->permission_id;
                }
                $request->session()->put('menuList', $menuArray);
            } else {
                $MenuList = DB::table('permissions')->get();
                $menuArray = array();
                foreach ($MenuList as $menu) {
                    $menuArray[] = $menu->id;
                }
                $request->session()->put('menuList', $menuArray);
                $request->session()->put('iRoleId', '0');
            }


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
                $request->session()->put('CompanyLogo', "https://massolutions.thenexus.co.in/global/assets/images/logo-small.png");
            }
            $this->changeCssColor($request, $companyInfo);

            return view('wladmin.index', compact('yearList', 'dashboarCount'));
        } else if (Auth::user()->role_id == 3) {

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
        $callSameDayResolve = DB::table('ticketmaster')
            ->select(DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"), DB::raw('count(*) as `total`'), DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"), DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`"))
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereIn('finalStatus', array(1, 4, 5))
            ->whereBetween('strEntryDate', [$datefrom, $dateto])
            ->where('ComplainDate', '>', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'))
            ->orderBy('strEntryDate', 'ASC')
            ->groupBy('month-year')
            ->get();
        //             $sql = Str::replaceArray('?', $callSameDayResolve->getBindings(), $callSameDayResolve->toSql());
        // dd($sql);
        $monthSameDayResolvearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        $percentageSameDayResolvearray = array('Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0, 'Jan' => 0, 'Feb' => 0, 'Mar' => 0);
        foreach ($callSameDayResolve as $call) {
            if (array_key_exists($call->month, $montharray)) {
                //$montharray[$call->month] = round(($call->total * 100) / $totalSum,2);
                $monthSameDayResolvearray[$call->month] = $call->total;
            }
        }
        foreach ($monthvisetotalarray as $key => $val) {
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

    public function getDashboardCount(Request $request)
    {

        $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, 'iUserId' => Auth::user()->id])->first();

        $yeardetail = DB::table('yearlog')->where('iYearId', $request->yearId)->first();
        $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
        $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));

        $totalTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            //->when($request->yearId, fn ($query, $yearId) => $query->where('strEntryDate','>=',$datefrom)->where('strEntryDate','<=',$dateto))
            ->where('OemCompannyId', Session::get('CompanyId'))
            //->where('OemCompannyId', $CompanyMaster->iCompanyId)
            ->count();
        $openTicket = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            //->when($request->yearId, fn ($query, $yearId) => $query->where('strEntryDate','>=',$datefrom)->where('strEntryDate','<=',$dateto))
            ->where('OemCompannyId', Session::get('CompanyId'))
            //->where('OemCompannyId', $CompanyMaster->iCompanyId)
            //->where('ComplainDate', '<=', DB::raw('DATE_SUB(ResolutionDate, INTERVAL 24 HOUR)'))
            ->whereIn('finalStatus', array(0, 3))
            ->count();


        $custCompanies = TicketMaster::where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
            ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId');
        if ($request->fMonth != "") {
            $custCompanies->whereMonth('ticketmaster.strEntryDate', $request->fMonth);
        } else {
            $custCompanies->whereMonth('ticketmaster.strEntryDate', date('m'));
        }
        if ($request->yearId != "") {
            $custCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $dateto);
        } else {
            $custCompanies->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $dateto);
        }
        // ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('ticketmaster.strEntryDate', $fMonth))
        // ->when($request->yearId, fn ($query, $yearId) => $query->where('ticketmaster.strEntryDate', '>=', $datefrom)

        $custCompany = $custCompanies->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
            ->distinct('ticketmaster.iCompanyId')
            //->toSql();
            ->count('ticketmaster.iCompanyId');


        $NewCompany = TicketMaster::where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
            ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
            ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
            ->distinct('ticketmaster.iCompanyId')
            ->count('ticketmaster.iCompanyId');

        $pastMonthDate = date('Y-' . $request->fMonth . '-01');
        $customer = DB::table('ticketmaster')
            ->when($request->fMonth, fn ($query, $fMonth) => $query->whereMonth('strEntryDate', $fMonth))
            // ->when($request->yearId, fn ($query, $yearId) => $query->whereBetween('strEntryDate', [$datefrom, $dateto]))
            ->when($request->yearId, fn ($query, $yearId) => $query->where('strEntryDate', '>=', $datefrom)->where('strEntryDate', '<=', $dateto))
            // ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('OemCompannyId', Session::get('CompanyId'))
            //->whereNotIn('ticketmaster.CustomerMobile', DB::table('ticketmaster')->select('CustomerMobile')->where('strEntryDate', '<=', $datefrom)->get()->toArray())
            ->whereNotIn(
                'ticketmaster.CustomerMobile',
                function ($query) use ($pastMonthDate) {
                    $query->select('CustomerMobile')
                        ->from(with(new TicketMaster)->getTable())
                        ->where('strEntryDate', '<', $pastMonthDate);
                }
            )
            ->groupBy('ticketmaster.CustomerMobile')
            ->get();

        $NewCustomer = DB::table('ticketmaster')
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->whereNotIn(
                'ticketmaster.CustomerMobile',
                function ($query) use ($pastMonthDate) {
                    $query->select('CustomerMobile')
                        ->from(with(new TicketMaster)->getTable())
                        ->where('strEntryDate', '<', $pastMonthDate);
                }
            )
            ->groupBy('ticketmaster.CustomerMobile')
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
        }
        $ticketCallQuery = DB::select("select sum(t1) as `tCall` from
            (select count(*) as t1 from ticketmaster where
            " . $where . "
            UNION ALL
            select count(*) as t1 from ticketlog,ticketmaster where ticketlog.iticketId =ticketmaster.iTicketId
             and " . $where . ") tbl");
        $ticketCall = $ticketCallQuery[0]->tCall;
        $dashboarCount = array('call' => $ticketCall, 'ticket' => $totalTicket, 'oTicket' => $openTicket, 'custCompany' => $custCompany,  'NewCompany' => $NewCompany, 'customer' => count($customer), 'NewCustomer' => count($NewCustomer));

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

        $resolvedbyl2level = DB::table('ticketmaster')
            ->select(
                DB::raw("DATE_FORMAT(strEntryDate,'%Y-%m') as `month-year`"),
                DB::raw('count(*) as `total`'),
                DB::raw("DATE_FORMAT(strEntryDate,'%b') as `month`"),
                DB::raw("DATE_FORMAT(strEntryDate,'%c') as `monthNo`")
            )
            ->where('OemCompannyId', Session::get('CompanyId'))
            ->where('LevelId', '1')
            ->where('iLevel2CallAttendentId', '!=', '0')
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
}
