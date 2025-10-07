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
use App\Models\Role;
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
                ->where(['callattendent.isDelete' => 0, "iExecutiveLevel" => 1])
                ->when($request->search_first_name, fn($query, $search_first_name) => $query->where('callattendent.strFirstName', 'like', '%' . $search_first_name . '%'))
                ->when($request->search_contact, fn($query, $search_contact) => $query->where('callattendent.strContact', $search_contact))
                ->when($request->search_email, fn($query, $search_email) => $query->where('callattendent.strEmailId', $search_email))
                ->when($request->search_status, fn($query, $search_status) => $query->where('callattendent.iStatus', $search_status))
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('callattendent.strEntryDate', [$formDate, $toDate]))
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
            // dd($companyMasters);
            return view('admin.call_attendant.add', compact('CallAttendent', 'companyMasters'));
        } else {
            return redirect()->route('home');
        }
    }

    public function emailvalidate(Request $req)
    {
        $email = $req->email;
        //$useremail = CallAttendent::where("strEmailId", "LIKE", "\\" . $email . "%")->count();
        if (isset($req->iUserId)) {
            $useremail = User::where("email", "LIKE", "\\" . $email . "%")->where("id", "!=", $req->iUserId)->count();
        } else {
            $useremail = User::where("email", "LIKE", "\\" . $email . "%")->count();
        }
        if ($useremail > 0) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function create(Request $request)
    {
        // dd($request);
        if (Auth::User()->role_id == 1) {
            try {

                $User = array(
                    'first_name' => $request->strFirstName,
                    'last_name' => $request->strLastName,
                    'mobile_number' => $request->strContact,
                    'email' => $request->strEmailId,
                    'password' => Hash::make($request->strPassword),
                    'isCanSwitchProfile' => $request->isCanSwitchProfile == "Yes" ?  1 : 0,
                    'role_id' => 3,
                );
                // dd($User);
                $iUserId = DB::table('users')->insertGetId($User);

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                $Data = array(
                    'strFirstName' => $request->strFirstName,
                    'strLastName' => $request->strLastName,
                    'strContact' => $request->strContact,
                    'strEmailId' => $request->strEmailId,
                    //'strPassword' => $request->strPassword,
                    "iUserId" => $iUserId,
                    'iExecutiveLevel' => 1,
                    'iOEMCompany' => 0,
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

                $icounter = 0;
                foreach ($request->iOEMCompanyId as $Company) {

                    if ($request->iOEMCompanyRoleAddMore[$icounter] != null) {

                        $multiplecompanyroledata = array(
                            'icallattendent' => $callattendent,
                            'userid' => $iUserId,
                            'iExecutiveLevel' => 1,
                            'iOEMCompany' => $Company,
                            'iRoleId' => $request->iOEMCompanyRoleAddMore[$icounter],
                            'created_at' => date("Y-m-d H:i:s"),
                            'strIP' => $request->ip()
                        );
                        DB::table('multiplecompanyrole')->insertGetId($multiplecompanyroledata);
                    }
                    $icounter++;
                }



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
                    'ToEmail' => $request->strEmailId,
                    'Subject' => $SendEmailData->strSubject
                );

                $mail = Mail::send('emails.userAdd', [
                    'user' => $User,
                    'password' => $request->strPassword,
                    'Company' => 'Mas Solution',
                    'data' => $data
                ], function ($message) use ($msg) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                });

                $save = $request->save;

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
        // dd($id);
        if (Auth::User()->role_id == 1) {
            $CallAttendent = CallAttendent::where(['isDelete' => 0, 'iUserId' => $id])->first();
            $companyMasters = CompanyMaster::orderBy('strOEMCompanyName', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            $User = User::where(['status' => 1, 'id' => $id])->first();

            $data = DB::table('multiplecompanyrole')->where(['iStatus' => 1, 'isDelete' => 0, 'userid' => $id])->first();
            // dd($data);


            return view('admin.call_attendant.edit', compact('CallAttendent', 'companyMasters', 'User', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        // dd($request);
        $isCanSwitchProfile = 0;
        if ($request->isCanSwitchProfile == "Yes") {
            $isCanSwitchProfile = 1;
        }

        if (Auth::User()->role_id == 1) {
            $User = array(
                'first_name' => $request->strFirstName,
                'last_name' => $request->strLastName,
                'mobile_number' => $request->strContact,
                'email' => $request->strEmailId,
                'isCanSwitchProfile' => $isCanSwitchProfile ?? 0,
            );
            DB::table('users')->where('id', '=', $request->iUserId)->update($User);
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            $Data = array(
                'strFirstName' => $request->strFirstName,
                'strLastName' => $request->strLastName,
                'strContact' => $request->strContact,
                'strEmailId' => $request->strEmailId,
                "iUserId" => $request->iUserId,
                'iExecutiveLevel' => 1,
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

            $datadelete = DB::table('multiplecompanyrole')->where('userid', '=', $request->iUserId)->delete();

            $icounter = 0;
            foreach ($request->iOEMCompanyId as $Company) {

                if ($request->iOEMCompanyRoleAddMore[$icounter] != null) {

                    $multiplecompanyroledata = array(
                        'icallattendent' => $request->iCallAttendentId,
                        'userid' => $request->iUserId,
                        'iExecutiveLevel' => 1,
                        'iOEMCompany' => $Company,
                        'iRoleId' => $request->iOEMCompanyRoleAddMore[$icounter],
                        'updated_at' => date("Y-m-d H:i:s"),
                        'strIP' => $request->ip()
                    );
                    DB::table('multiplecompanyrole')->insertGetId($multiplecompanyroledata);
                }
                $icounter++;
            }

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


    public function mappingcompanyrole(Request $request)
    {
        // dd($request);
        if (Auth::User()->role_id == 1) {
            $Mapping = Role::where(['iStatus' => 1, 'isDelete' => 0])
                ->where(['iCompanyId' => $request->company])
                ->get();
            // dd($Mapping);

            $html = "";
            $html = "<option label='Please Select' value=''> -- Select-- </option>";
            foreach ($Mapping as $mapping) {
                $html .= "<option value='" . $mapping->id . "'>" . $mapping->name . "</option>";
            }

            return $html;
        } else {
            return redirect()->route('home');
        }
    }
}
