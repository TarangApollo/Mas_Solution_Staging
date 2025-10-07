<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WlUser;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\Hash;
use App\Models\SendEmailDetails;
use Illuminate\Support\Facades\Mail;
use App\Models\infoTable;
use App\Models\Role;
use App\Models\Loginlog;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\CompanyInfo;
use Auth;

class WlUserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $search_first_name = $request->search_first_name;
            $search_contact = $request->search_contact;
            $search_email = $request->search_email;
            $search_role = $request->search_role;
            $search_status = $request->search_status;
            $WlUsers = WlUser::orderBy('iCompanyUserId', 'DESC')
                ->select('companyuser.*', 'users.first_name', 'users.last_name', 'roles.name')
                ->leftjoin('users', 'users.id', '=', 'companyuser.iUserId')
                ->leftjoin('roles', 'roles.id', '=', 'companyuser.iRoleId')
                ->where(['companyuser.isDelete' => 0])
                ->where(['companyuser.iCompanyId' => Session::get('CompanyId')])
                ->when($request->search_first_name, fn($query, $search_first_name) => $query->where('companyuser.strFirstName', 'like', '%' . $search_first_name . '%'))
                ->when($request->search_contact, fn($query, $search_contact) => $query->where('companyuser.strContact', $search_contact))
                ->when($request->search_email, fn($query, $search_email) => $query->where('companyuser.strEmail', $search_email))
                ->when($request->search_role, fn($query, $search_role) => $query->where('companyuser.iRoleId', $search_role))
                ->when($request->search_status, fn($query, $search_status) => $query->where('companyuser.iStatus', $search_status))
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('companyuser.strEntryDate', [$formDate, $toDate]))
                ->get();

            $roleList = Role::orderBy('roles.id', 'DESC')
                ->where(['roles.isDelete' => 0, 'roles.iStatus' => 1, "roles.iCompanyId" => Session::get('CompanyId')])
                ->get();
            return view('wladmin.user.index', compact('WlUsers', 'search_email', 'search_status', 'search_role', 'search_role', 'search_first_name', 'search_contact', 'roleList'));
        } else {
            return redirect()->route('home');
        }
    }

    public function infoindex(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $WlUser = WlUser::orderBy('iCompanyUserId', 'DESC')->where(['isDelete' => 0,  "iUserId" => $id])->first();
            $infoTables = infoTable::where(["tableName" => "companyuser", "tableAutoId" => $WlUser->iCompanyUserId])->orderBy('id', 'Desc')->limit(10)->get();
            // $Loginlogs = Loginlog::select(DB::select('DATE_FORMAT(STR_TO_DATE(strEntryDate,"%Y-%m-%d %T"),"%d-%m-%Y") as enterdate'))->where(["userId" => $id])->orderBy('strEntryDate', 'Desc')->get();
            $Loginlogs = Loginlog::where(["userId" => $id])->orderBy('strEntryDate', 'Desc')->get();

            return view('wladmin.user.info', compact('WlUser', 'infoTables', 'Loginlogs'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        if (Auth::User()->role_id == 2) {
            $WlUser = WlUser::orderBy('iCompanyUserId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();


            $Roles = Role::orderBy('roles.id', 'DESC')
                ->where(['roles.isDelete' => 0, 'roles.iStatus' => 1, "roles.iCompanyId" => Session::get('CompanyId')])
                ->get();
            return view('wladmin.user.add', compact('WlUser', 'CompanyMaster', 'Roles'));
        } else {
            return redirect()->route('home');
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        if (Auth::User()->role_id == 2) {
            try {
                $icounter = 0;
                // foreach ($request->strFirstName as $strFirstName) {
                //     $hashPassword = Hash::make($request->strPassword[$icounter]);
                //     $User = array(
                //         'first_name' => $strFirstName,
                //         'last_name' => $request->strLastName[$icounter],
                //         'mobile_number' => $request->strContact[$icounter],
                //         'email' => $request->strEmail[$icounter],
                //         'password' => $hashPassword,
                //         'role_id' => 2,
                //     );
                //     $iUserId = DB::table('users')->insertGetId($User);
                //     $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                //     $Data = array(
                //         'iCompanyId' => $request->iCompanyId,
                //         'strFirstName' => $strFirstName,
                //         'strLastName' => $request->strLastName[$icounter],
                //         'strContact' => $request->strContact[$icounter],
                //         'strEmail' => $request->strEmail[$icounter],
                //         'strPassword' => $hashPassword,
                //         "iUserId" => $iUserId,
                //         'iRoleId' => $request->iRoleId[$icounter],
                //         'iEntryBy' => $session
                //     );

                //     $companyuser = DB::table('companyuser')->insertGetId($Data);

                //     $userdata = \App\Models\User::whereId($session)->first();
                //     $infoArr = array(
                //         'tableName'    => "companyuser",
                //         'tableAutoId'    => $companyuser,
                //         'tableMainField'  => "First Name,Last Name,Contact,Email,Role",
                //         'action'     => "Inserted",
                //         'strEntryDate' => date("Y-m-d H:i:s"),
                //         'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                //     );

                //     $Info = infoTable::create($infoArr);

                //     $SendEmailData = DB::table('sendemaildetails')
                //         ->where(['id' => 2])
                //         ->first();
                //     // $msg = array(
                //     //     'FromMail' => $SendEmailDetails->strFromMail,
                //     //     'Title' => $SendEmailDetails->strTitle,
                //     //     'ToEmail' => $request->strEmail[$icounter],
                //     //     'Subject' => $SendEmailDetails->strSubject
                //     // );
                //     $CompanyData = CompanyMaster::where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyId' => $request->iCompanyId])->first();

                //     if(!empty($CompanyData)){
                //         // Company Details and link
                //         $companyInfo = CompanyInfo::where('iCompanyId','=',$request->iCompanyId)->first();
                //         if(!empty($companyInfo)){
                //             $data = array(
                //                 "strCompanyName" => $companyInfo->strCompanyName,
                //                 "Address1" => $companyInfo->Address1,
                //                 "address2" => $companyInfo->address2,
                //                 "strCity" => $companyInfo->strCity,
                //                 "strState" => $companyInfo->strState,
                //                 "strCountry" => $companyInfo->strCountry,
                //                 "instaLink" => $companyInfo->instaLink,
                //                 "twitterLink" => $companyInfo->twitterlink,
                //                 "facebookLink" => $companyInfo->facebookLink,
                //                 "linkedinLink" => $companyInfo->linkedinlink,
                //                 "supportEmail" => $companyInfo->EmailId,
                //                 "ContactNo" => $companyInfo->ContactNo,
                //                 "Logo" => $companyInfo->strLogo
                //             );
                //             $msg = array(
                //                 'FromMail' => $companyInfo->EmailId,
                //                 'Title' => $companyInfo->strCompanyName,
                //                 'ToEmail' => $request->strEmail[$icounter],
                //                 'Subject' => $SendEmailData->strSubject
                //             );
                //         } else {
                //             // Mas Solution and link
                //             $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                //             $data = array(
                //                 "strCompanyName" => $companyInfo->strCompanyName,
                //                 "Address1" => $companyInfo->Address1,
                //                 "address2" => $companyInfo->address2,
                //                 "strCity" => $companyInfo->strCity,
                //                 "strState" => $companyInfo->strState,
                //                 "strCountry" => $companyInfo->strCountry,
                //                 "instaLink" => $companyInfo->instaLink,
                //                 "twitterLink" => $companyInfo->twitterlink,
                //                 "facebookLink" => $companyInfo->facebookLink,
                //                 "linkedinLink" => $companyInfo->linkedinlink,
                //                 "supportEmail" => $companyInfo->EmailId,
                //                 "ContactNo" => $companyInfo->ContactNo,
                //                 "Logo" => $companyInfo->strLogo
                //             );
                //             $msg = array(
                //                 'FromMail' => $SendEmailData->strFromMail,
                //                 'Title' => $SendEmailData->strTitle,
                //                 'ToEmail' => $request->strEmail[$icounter],
                //                 'Subject' => $SendEmailData->strSubject
                //             );
                //         }
                //     } else {
                //         // Mas Solution and link
                //         $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                //         $data = array(
                //             "strCompanyName" => $companyInfo->strCompanyName,
                //             "Address1" => $companyInfo->Address1,
                //             "address2" => $companyInfo->address2,
                //             "strCity" => $companyInfo->strCity,
                //             "strState" => $companyInfo->strState,
                //             "strCountry" => $companyInfo->strCountry,
                //             "instaLink" => $companyInfo->instaLink,
                //             "twitterLink" => $companyInfo->twitterlink,
                //             "facebookLink" => $companyInfo->facebookLink,
                //             "linkedinLink" => $companyInfo->linkedinlink,
                //             "supportEmail" => $companyInfo->EmailId,
                //             "ContactNo" => $companyInfo->ContactNo,
                //             "Logo" => $companyInfo->strLogo
                //         );
                //         $msg = array(
                //             'FromMail' => $SendEmailData->strFromMail,
                //             'Title' => $SendEmailData->strTitle,
                //             'ToEmail' => $request->strEmail[$icounter],
                //             'Subject' => $SendEmailData->strSubject
                //         );
                //     }
                //     $mail = Mail::send('emails.userAdd', ['user' => $User, 'password' => $request->strPassword[$icounter], 'Company' => $CompanyData->strOEMCompanyName,'data' => $data], function ($message) use ($msg) {
                //         $message->from($msg['FromMail'], $msg['Title']);
                //         $message->to($msg['ToEmail'])->subject($msg['Subject']);
                //     });

                //     $icounter++;
                // }
                // dd($request);
                // $request->validate([
                //     'strFirstName'    => 'required',
                //     'strLastName'     => 'required',
                //     'strEmail'         => 'required|unique:users,email',
                //     'strContact' => 'required|numeric|digits:10',
                //     'iRoleId'       =>  'required|exists:roles,id',
                //     'isCanSwitchProfile'       =>  'required|numeric|in:0,1',
                // ]);

                $hashPassword = Hash::make($request->strPassword);
                $User = array(
                    'first_name' => $request->strFirstName,
                    'last_name' => $request->strLastName,
                    'mobile_number' => $request->strContact,
                    'email' => $request->strEmail,
                    'password' => $hashPassword,
                    'isCanSwitchProfile' => $request->isCanSwitchProfile == "on" ?  1 : 0,
                    'role_id' => 2,
                );
                $iUserId = DB::table('users')->insertGetId($User);
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                if ($request->isCanSwitchProfile == "on") {
                    $Data = array(
                        'strFirstName' => $request->strFirstName,
                        'strLastName' => $request->strLastName,
                        'strContact' => $request->strContact,
                        'strEmailId' => $request->strEmail,
                        //'strPassword' => $request->strPassword,
                        "iUserId" => $iUserId,
                        'iExecutiveLevel' => 2,
                        'iOEMCompany' => $request->iCompanyId,
                        'iEntryBy' => $session
                    );
                    DB::table('callattendent')->insertGetId($Data);
                }
                $Data = array(
                    'iCompanyId' => $request->iCompanyId,
                    'strFirstName' => $request->strFirstName,
                    'strLastName' => $request->strLastName,
                    'strContact' => $request->strContact,
                    'strEmail' => $request->strEmail,
                    'strPassword' => $hashPassword,
                    "iUserId" => $iUserId,
                    'iRoleId' => $request->iRoleId ?? 0,
                    'iEntryBy' => $session
                );

                $companyuser = DB::table('companyuser')->insertGetId($Data);

                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "companyuser",
                    'tableAutoId'    => $companyuser,
                    'tableMainField'  => "First Name,Last Name,Contact,Email,Role",
                    'action'     => "Inserted",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );

                $Info = infoTable::create($infoArr);

                $SendEmailData = DB::table('sendemaildetails')
                    ->where(['id' => 2])
                    ->first();
                // $msg = array(
                //     'FromMail' => $SendEmailDetails->strFromMail,
                //     'Title' => $SendEmailDetails->strTitle,
                //     'ToEmail' => $request->strEmail,
                //     'Subject' => $SendEmailDetails->strSubject
                // );
                $CompanyData = CompanyMaster::where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyId' => $request->iCompanyId])->first();

                if (!empty($CompanyData)) {
                    // Company Details and link
                    $companyInfo = CompanyInfo::where('iCompanyId', '=', $request->iCompanyId)->first();
                    if (!empty($companyInfo)) {
                        $data = array(
                            "strCompanyName" => $companyInfo->strCompanyName,
                            "Address1" => $companyInfo->Address1,
                            "address2" => $companyInfo->address2,
                            "strCity" => $companyInfo->strCity,
                            "strState" => $companyInfo->strState,
                            "strCountry" => $companyInfo->strCountry,
                            "instaLink" => $companyInfo->instaLink,
                            "twitterLink" => $companyInfo->twitterlink,
                            "facebookLink" => $companyInfo->facebookLink,
                            "linkedinLink" => $companyInfo->linkedinlink,
                            "supportEmail" => $companyInfo->EmailId,
                            "ContactNo" => $companyInfo->ContactNo,
                            "Logo" => $companyInfo->strLogo
                        );
                        $msg = array(
                            'FromMail' => $companyInfo->EmailId   ?? $CompanyData->EmailId,
                            'Title' => $companyInfo->strCompanyName,
                            'ToEmail' => $request->strEmail,
                            'Subject' => $SendEmailData->strSubject
                        );
                    } else {
                        // Mas Solution and link
                        $companyInfo = CompanyInfo::where('iCompanyInfoId', '=', '1')->first();
                        $data = array(
                            "strCompanyName" => $companyInfo->strCompanyName,
                            "Address1" => $companyInfo->Address1,
                            "address2" => $companyInfo->address2,
                            "strCity" => $companyInfo->strCity,
                            "strState" => $companyInfo->strState,
                            "strCountry" => $companyInfo->strCountry,
                            "instaLink" => $companyInfo->instaLink,
                            "twitterLink" => $companyInfo->twitterlink,
                            "facebookLink" => $companyInfo->facebookLink,
                            "linkedinLink" => $companyInfo->linkedinlink,
                            "supportEmail" => $companyInfo->EmailId,
                            "ContactNo" => $companyInfo->ContactNo,
                            "Logo" => $companyInfo->strLogo
                        );
                        $msg = array(
                            'FromMail' => $SendEmailData->strFromMail,
                            'Title' => $SendEmailData->strTitle,
                            'ToEmail' => $request->strEmail,
                            'Subject' => $SendEmailData->strSubject
                        );
                    }
                } else {
                    // Mas Solution and link
                    $companyInfo = CompanyInfo::where('iCompanyInfoId', '=', '1')->first();
                    $data = array(
                        "strCompanyName" => $companyInfo->strCompanyName,
                        "Address1" => $companyInfo->Address1,
                        "address2" => $companyInfo->address2,
                        "strCity" => $companyInfo->strCity,
                        "strState" => $companyInfo->strState,
                        "strCountry" => $companyInfo->strCountry,
                        "instaLink" => $companyInfo->instaLink,
                        "twitterLink" => $companyInfo->twitterlink,
                        "facebookLink" => $companyInfo->facebookLink,
                        "linkedinLink" => $companyInfo->linkedinlink,
                        "supportEmail" => $companyInfo->EmailId,
                        "ContactNo" => $companyInfo->ContactNo,
                        "Logo" => $companyInfo->strLogo
                    );
                    $msg = array(
                        'FromMail' => $SendEmailData->strFromMail,
                        'Title' => $SendEmailData->strTitle,
                        'ToEmail' => $request->strEmail,
                        'Subject' => $SendEmailData->strSubject
                    );
                }
                $mail = Mail::send('emails.userAdd', ['user' => $User, 'password' => $request->strPassword, 'Company' => $CompanyData->strOEMCompanyName, 'data' => $data], function ($message) use ($msg) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                });

                $icounter++;

                $save = $request->save;
                Session::flash('Success', 'Company User Added Successfully.');
                if ($save == '1') {
                    echo  $iUserId;
                } else {
                    return redirect()->route('user.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo 0;
                } else {
                    return redirect()->route('user.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function editview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $WlUser = WlUser::where(['isDelete' => 0, 'iUserId' => $id])->first();
            $Roles = Role::orderBy('roles.id', 'DESC')
                ->where(['roles.isDelete' => 0, 'roles.iStatus' => 1, "roles.iCompanyId" => Session::get('CompanyId')])
                ->get();
            $User = User::where(['status' => 1, 'id' => $id])->first();

            return view('wladmin.user.edit', compact('WlUser', 'Roles', 'User'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        // dd($request);
        $isCanSwitchProfile = 0;
        if ($request->isCanSwitchProfile == "on") {
            $isCanSwitchProfile = 1;
        }

        if (Auth::User()->role_id == 2) {
            try {
                $User = array(
                    'first_name' => $request->strFirstName,
                    'last_name' => $request->strLastName,
                    'mobile_number' => $request->strContact,
                    'email' => $request->strEmail,
                    'isCanSwitchProfile' => $isCanSwitchProfile ?? 0,
                    'role_id' => 2
                );
                DB::table('users')->where('id', '=', $request->iUserId)->update($User);



                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                $companyuser = DB::table('companyuser')->where('iCompanyUserId', '=', $request->iCompanyUserId)->first();
                $callattendent = DB::table('callattendent')->where("iUserId", $request->iUserId)->first();
                if ($request->isCanSwitchProfile == "on") {
                    if (empty($callattendent)) {
                        $Data = array(
                            'strFirstName' => $request->strFirstName,
                            'strLastName' => $request->strLastName,
                            'strContact' => $request->strContact,
                            'strEmailId' => $request->strEmail,
                            //'strPassword' => $request->strPassword,
                            "iUserId" => $request->iUserId,
                            'iExecutiveLevel' => 2,
                            'iOEMCompany' => $companyuser->iCompanyId,
                            'iEntryBy' => $session
                        );
                        DB::table('callattendent')->insertGetId($Data);
                    }
                } else {
                    if (!empty($callattendent)) {
                        DB::table('callattendent')->where("iUserId", $request->iUserId)->delete();
                    }
                }

                $Data = array(
                    'strFirstName' => $request->strFirstName,
                    'strLastName' => $request->strLastName,
                    'strContact' => $request->strContact,
                    'strEmail' => $request->strEmail,
                    "iUserId" => $request->iUserId,
                    'iRoleId' => $request->iRoleId ?? 0,
                    'iEntryBy' => $session
                );
                DB::table('companyuser')->where('iCompanyUserId', '=', $request->iCompanyUserId)->update($Data);

                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "companyuser",
                    'tableAutoId'    => $request->iCompanyUserId,
                    'tableMainField'  => "First Name,Last Name,Contact,Email,Role",
                    'action'     => "Updated",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Company User updated Successfully.');
                $save = $request->save;
                if ($save == '1') {
                    echo  $request->iCompanyUserId;
                } else {
                    return redirect()->route('user.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo 0;
                } else {
                    return redirect()->route('user.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if (Auth::User()->role_id == 2) {
            $WlUser = WlUser::where(['iStatus' => 1, 'isDelete' => 0, 'iUserId' => $Id])->first();
            User::where(['status' => 1, 'id' => $Id])->delete();
            WlUser::where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyUserId' => $WlUser->iCompanyUserId])->delete();
            Session::flash('Success', 'Company User Deleted Successfully.');
            return redirect()->route('user.index');
        } else {
            return redirect()->route('home');
        }
    }

    public function updateStatus(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            if ($request->status == 1) {
                User::where(['id' => $request->UserId])->update(['status' => 0]);
                WlUser::where(['isDelete' => 0, 'iUserId' => $request->UserId])->update(['iStatus' => 0]);
            } else {
                User::where(['id' => $request->UserId])->update(['status' => 1]);
                WlUser::where(['isDelete' => 0, 'iUserId' => $request->UserId])->update(['iStatus' => 1]);
            }
            echo 1;
        } else {
            return redirect()->route('home');
        }
    }
    public function emailvalidate(Request $req)
    {
        $email = $req->email;
        if (isset($req->ID)) {
            // $useremail = WlUser::where("strEmail", "LIKE", "\\" . $email . "%")->where('iCompanyUserId', '!=', $req->ID)->count();
            $WlUser = WlUser::where('iCompanyUserId', '=', $req->ID)->first();
            $useremail = User::where("email", "LIKE", "\\" . $email . "%")->where("id", '!=', $WlUser->iUserId)->count();
        } else {
            //$useremail = WlUser::where("strEmail", "LIKE", "\\" . $email . "%")->count();
            $useremail = User::where("email", "LIKE", "\\" . $email . "%")->count();
        }
        if ($useremail > 0) {
            echo 0;
        } else {
            echo 1;
        }
    }
}
