<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\DB;
use App\Models\infoTable;
use Illuminate\Support\Facades\Session;
use App\Models\MessageMaster;
use Auth;
class CompanyInfoController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->role_id == 1){
            $CompanyInfo = CompanyInfo::orderBy('iCompanyInfoId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1,'iCompanyId' => 0])->first();
            $messageMaster = MessageMaster::where(['isDelete' => 0, 'iStatus' => 1,"iCompanyId" => 0])->first();
            return view('admin.company_info.info', compact('CompanyInfo','messageMaster'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        // $CompanyInfo = CompanyInfo::where(['isDelete' => 0, 'iStatus' => 1])->get();
        // return view('company_info.add', compact('CompanyInfo'));
    }

    // public function create(Request $request)
    // {
    //     $Data = array([
    //         'strOEMCompanyName' => $request->strOEMCompanyName,
    //         'strOEMCompanyId' => $request->strOEMCompanyId,
    //         'ContactPerson' => $request->ContactPerson,
    //         'EmailId' => $request->EmailId,
    //         'ContactNo' => $request->ContactNo,
    //         'Address1' => $request->Address1,
    //         'Address2' => $request->Address2,
    //         'Address3' => $request->Address3,
    //         'Pincode' => $request->Pincode,
    //         'iStateId' => $request->iStateId,
    //         'iCityId' => $request->iCityId,
    //         'strGSTNo' => $request->strGSTNo
    //     ]);
    //     //dd($Data);
    //     DB::table('companymaster')->insert($Data);
    //     return redirect()->route('company.index')->with('Success', 'Company Created Successfully.');
    // }

    public function editview(Request $request, $id)
    {
        // dd($Id);
        $Data = CompanyInfo::where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyInfoId' => $id])->first();
        //dd($Data);
        return  json_encode($Data);
    }

    public function GeneralDataStore(Request $request)
    {
        if(Auth::User()->role_id == 1){
            $request->validate([
                'EmailId'         => 'required',
                'ContactNo' => 'required|numeric|digits:10',
                'strCity'   => 'required',
                'strState'   => 'required',
                'strCountry'   => 'required',
                'Address1'      => 'required',
                'Pincode'      => 'required'
            ]);
            try {
                $data = array(
                    'strCompanyName' => 'Mas Solution',
                    'Address1' => $request->Address1,
                    'address2' => $request->address2,
                    'strCity' => $request->strCity,
                    'strState' => $request->strState,
                    'strCountry' => $request->strCountry,
                    'Pincode' => $request->Pincode,
                    'Phone' => $request->Phone,
                    'ContactNo' => $request->ContactNo,
                    'EmailId' => $request->EmailId,
                    'AnotherEmailId' => $request->AnotherEmailId
                );
                $iCompanyInfoId = 0;
                $action = "";
                if($request->CompanyInfoId == ""){
                    $iCompanyInfoId = CompanyInfo::create($data);
                    $action = "Insert";
                } else{
                    $iCompanyInfoId = $request->CompanyInfoId;
                    CompanyInfo::where("iCompanyInfoId",'=',$request->CompanyInfoId)->update($data);
                    $action = "Update";
                }

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $userdata = \App\Models\User::whereId($session)->first();

                $infoArr = array(
                    'tableName'    => "companyinfo",
                    'tableAutoId'    => $iCompanyInfoId,
                    'tableMainField'  => "General Information",
                    'action'     => $action,
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                DB::commit();
                //return redirect()->route('company.index')->with('Success', 'Company Updated Successfully.');
                echo 1;
            } catch (\Throwable $th) {
                DB::rollBack();
                echo 0;
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function SocialMediaLinkStore(Request $request)
    {
        if(Auth::User()->role_id == 1){
            try {
                $data = array(
                    'facebookLink' => $request->facebookLink,
                    'instaLink' => $request->twitterlink,
                    'twitterlink' => $request->twitterlink,
                    'linkedinlink' => $request->linkedinlink
                );
                $iCompanyInfoId = 0;
                $action = "";
                if($request->iCompanyInfoId == ""){
                    $iCompanyInfoId = CompanyInfo::create($data);
                    $action = "Insert";
                } else{
                    $iCompanyInfoId = $request->iCompanyInfoId;
                    CompanyInfo::where("iCompanyInfoId",'=',$request->iCompanyInfoId)->update($data);
                    $action = "Update";
                }

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $userdata = \App\Models\User::whereId($session)->first();

                $infoArr = array(
                    'tableName'    => "companyinfo",
                    'tableAutoId'    => $iCompanyInfoId,
                    'tableMainField'  => "Social Media Links",
                    'action'     => $action,
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                DB::commit();
                Session::flash('Success', 'Company Social Media Links Successfully.');
                if($request->SocialMediaSave == 1){
                    echo 1;
                } else {
                    return redirect()->back();
                }
                //echo 1;
            } catch (\Throwable $th) {
                DB::rollBack();
                if($request->SocialMediaSave == 1){
                    echo 0;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());;
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function MessageforInactiveUserstore(Request $request){
        if(Auth::User()->role_id == 1){
            try {
                $data = array(
                    'strMessage' => $request->strMessage
                );
                $iMessageId = 0;
                $action = "";
                if($request->iMessageId == ""){
                    $iMessageId = MessageMaster::create($data);
                    $action = "Insert";
                } else{
                    $iMessageId = $request->iMessageId;
                    MessageMaster::where("iMessageId",'=',$request->iMessageId)->update($data);
                    $action = "Update";
                }

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $userdata = \App\Models\User::whereId($session)->first();

                $infoArr = array(
                    'tableName'    => "messagemaster",
                    'tableAutoId'    => $iMessageId,
                    'tableMainField'  => "Message for Inactive User",
                    'action'     => $action,
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                DB::commit();
                Session::flash('Success', 'Company Inactive User Message added Successfully.');
                if($request->MessageInactiveUserSave == 1){
                    echo 1;
                } else {
                    return redirect()->back();
                }
                //echo 1;
            } catch (\Throwable $th) {
                DB::rollBack();
                if($request->MessageInactiveUserSave == 1){
                    echo 0;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());;
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

}
