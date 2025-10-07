<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallAttendent;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\Hash;
use App\Models\SendEmailDetails;
use Mail;
use App\Models\infoTable;
use App\Models\Loginlog;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\CompanyInfo;
use Auth;

class CallAttendantController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 1) {
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
            $search_status = $request->search_status;
            $daterange = $request->daterange;
            $CallAttendent = CallAttendent::orderBy('iCallAttendentId', 'DESC')
                ->leftjoin('users', 'users.id', '=', 'callattendent.iEntryBy')
                ->where(['callattendent.isDelete' => 0])
                ->when($request->search_first_name, fn ($query, $search_first_name) => $query->where('callattendent.strFirstName', 'like', '%' . $search_first_name . '%'))
                ->when($request->search_contact, fn ($query, $search_contact) => $query->where('callattendent.strContact', $search_contact))
                ->when($request->search_email, fn ($query, $search_email) => $query->where('callattendent.strEmailId', $search_email))
                ->when($request->search_status, fn ($query, $search_status) => $query->where('callattendent.iStatus', $search_status))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('callattendent.strEntryDate', [$formDate, $toDate]))
                ->get();

            return view('admin.call_attendant.index', compact('CallAttendent', 'search_first_name', 'search_contact', 'search_email', 'search_status', 'daterange'));
        } else {
            return redirect()->route('home');
        }
    }

    public function infoindex(Request $request, $id)
    {
        if (Auth::User()->role_id == 1) {
            $CallAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => $id])->first();
            $infoTables = infoTable::where(["tableName" => "callattendent", "tableAutoId" => $CallAttendent->iCallAttendentId])->orderBy('id', 'Desc')->limit(10)->get();
            $Loginlogs = Loginlog::where(["userId" => $id])->orderBy('strEntryDate', 'Desc')->get();
            return view('admin.call_attendant.info', compact('CallAttendent', 'infoTables', 'Loginlogs'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        if (Auth::User()->role_id == 1) {
            $CallAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1])->get();
            $companyMasters = CompanyMaster::orderBy('strOEMCompanyName', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            return view('admin.call_attendant.add', compact('CallAttendent', 'companyMasters'));
        } else {
            return redirect()->route('home');
        }
    }

    public function emailvalidate(Request $req)
    {
        $email = $req->email;
        $useremail = CallAttendent::where("strEmailId", "LIKE", "\\" . $email . "%")->count();

        if ($useremail > 0) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function create(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $icounter = 0;
                foreach ($request->strFirstName as $strFirstName) {

                    $User = array(
                        'first_name' => $strFirstName,
                        'last_name' => $request->strLastName[$icounter],
                        'mobile_number' => $request->strContact[$icounter],
                        'email' => $request->strEmailId[$icounter],
                        'password' => Hash::make($request->strPassword[$icounter]),
                        'role_id' => 3,
                    );
                    $iUserId = DB::table('users')->insertGetId($User);
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                    $Data = array(
                        'strFirstName' => $strFirstName,
                        'strLastName' => $request->strLastName[$icounter],
                        'strContact' => $request->strContact[$icounter],
                        'strEmailId' => $request->strEmailId[$icounter],
                        //'strPassword' => $request->strPassword[$icounter],
                        "iUserId" => $iUserId,
                        'iExecutiveLevel' => $request->iExecutiveLevel[$icounter],
                        'iOEMCompany' => $request->iOEMCompany[$icounter] ?? 0,
                        'iEntryBy' => $session
                    );
                    $callattendent = DB::table('callattendent')->insertGetId($Data);

                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "callattendent",
                        'tableAutoId'    => $callattendent,
                        'tableMainField'  => "strFirstName,strLastName,strContact,strEmailId,iExecutiveLevel,iOEMCompany",
                        'action'     => "Inserted",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);

                    $SendEmailData = DB::table('sendemaildetails')
                        ->where(['id' => 2])
                        ->first();
                    if ($request->iExecutiveLevel[$icounter] == 1) {
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
                            'ToEmail' => $request->strEmailId[$icounter],
                            'Subject' => $SendEmailData->strSubject
                        );
                    }
                    if ($request->iExecutiveLevel[$icounter] == 2) {

                        // Company Details and link
                        $companyInfo = CompanyInfo::where('iCompanyId', '=', $request->iOEMCompany[$icounter])->first();
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
                                'FromMail' => $companyInfo->EmailId,
                                'Title' => $companyInfo->strCompanyName,
                                'ToEmail' => $request->strEmailId[$icounter],
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
                                'ToEmail' => $request->strEmailId[$icounter],
                                'Subject' => $SendEmailData->strSubject
                            );
                        }
                    }

                    // $msg = array(
                    //     'FromMail' => $SendEmailDetails->strFromMail,
                    //     'Title' => $SendEmailDetails->strTitle,
                    //     'ToEmail' => $request->strEmailId[$icounter],
                    //     'Subject' => $SendEmailDetails->strSubject
                    // );
                    $mail = Mail::send('emails.userAdd', ['user' => $User, 'password' => $request->strPassword[$icounter], 'Company' => 'Mas Solution', 'data' => $data], function ($message) use ($msg) {
                        $message->from($msg['FromMail'], $msg['Title']);
                        $message->to($msg['ToEmail'])->subject($msg['Subject']);
                    });
                    $icounter++;
                }
                $save = $request->save;
                //dd($save);
                Session::flash('Success', 'Call Attendant Created Successfully.');
                if ($save == '1') {
                    echo 1;
                } else {
                    return redirect()->route('call_attendant.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();

                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo $request->companyclient_id;;
                } else {
                    return redirect()->route('companyclient.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function editview(Request $request, $id)
    {
        if (Auth::User()->role_id == 1) {
            $CallAttendent = CallAttendent::where(['isDelete' => 0, 'iUserId' => $id])->first();
            $companyMasters = CompanyMaster::orderBy('strOEMCompanyName', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            return view('admin.call_attendant.edit', compact('CallAttendent', 'companyMasters'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $User = array(
                'first_name' => $request->strFirstName,
                'last_name' => $request->strLastName,
                'mobile_number' => $request->strContact,
                'email' => $request->strEmailId,
            );
            DB::table('users')->where('id', '=', $request->iUserId)->update($User);
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            $Data = array(
                'strFirstName' => $request->strFirstName,
                'strLastName' => $request->strLastName,
                'strContact' => $request->strContact,
                'strEmailId' => $request->strEmailId,
                "iUserId" => $request->iUserId,
                'iExecutiveLevel' => $request->iExecutiveLevel,
                'iOEMCompany' => $request->iOEMCompany,
                'iEntryBy' => $session
            );
            DB::table('callattendent')->where('iCallAttendentId', '=', $request->iCallAttendentId)->update($Data);

            $userdata = \App\Models\User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "callattendent",
                'tableAutoId'    => $request->iCallAttendentId,
                'tableMainField'  => "strFirstName,strLastName,strContact,strEmailId,iExecutiveLevel,iOEMCompany",
                'action'     => "Updated",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);
            return redirect()->route('call_attendant.index')->with('Success', 'Call Attendant Updated Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if (Auth::User()->role_id == 1) {
            $CallAttendent = CallAttendent::where(['iStatus' => 1, 'isDelete' => 0, 'iUserId' => $Id])->first();
            User::where(['status' => 1, 'id' => $Id])->delete();
            // $root = $_SERVER['DOCUMENT_ROOT'];
            // $destinationpath = $root . '/Testimonial/';
            // unlink($destinationpath . $User->photo);
            CallAttendent::where(['iStatus' => 1, 'isDelete' => 0, 'iCallAttendentId' => $CallAttendent->iCallAttendentId])->delete();

            return redirect()->route('call_attendant.index')->with('Success', 'Call Attendent Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }

    public function updateStatus(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            if ($request->status == 1) {
                User::where(['id' => $request->UserId])->update(['status' => 0]);
                CallAttendent::where(['isDelete' => 0, 'iUserId' => $request->UserId])->update(['iStatus' => 0]);
            } else {
                User::where(['id' => $request->UserId])->update(['status' => 1]);
                CallAttendent::where(['isDelete' => 0, 'iUserId' => $request->UserId])->update(['iStatus' => 1]);
            }
            echo 1;
        } else {
            return redirect()->route('home');
        }
    }
}
