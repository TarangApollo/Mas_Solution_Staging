<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\DB;
use App\Models\infoTable;
use Illuminate\Support\Facades\Session;
use App\Models\Component;
use App\Models\SubComponent;
use App\Models\ResolutionCategory;
use App\Models\IssueType;
use App\Models\CallCompetency;
use App\Models\CompanyInfo;
use App\Models\SupportType;
use App\Models\System;
use App\Models\User;
use App\Models\CompanyClient;
use App\Models\CompanyClientProfile;
use App\Models\Distributor;
use App\Models\CallAttendent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\OemCompanyModule;
use App\Models\ModuleMaster;

class CompanyMasterController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $search_company = $request->search_company;
            $search_status = $request->search_status;
            $Company = CompanyMaster::select('companymaster.*', 'citymaster.strCityName', 'users.first_name', 'users.last_name')
                ->orderBy('strOEMCompanyName', 'ASC')
                ->leftjoin('users', 'users.id', '=', 'companymaster.iEntryBy')
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'companymaster.iCityId')
                ->where(['companymaster.isDelete' => 0])
                ->when($request->search_company, fn($query, $search_company) => $query->where('companymaster.iCompanyId', $search_company))
                ->when($request->search_status, fn($query, $search_status) => $query->where('companymaster.iStatus', $search_status))
                ->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            return view('admin.company.index', compact('Company', 'search_company', 'search_status', 'CompanyMaster'));
        } else {
            return redirect()->route('home');
        }
    }

    public function infoindex(Request $request, $id)
    {
        if (Auth::User()->role_id == 1) {
            $Company = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1, 'iCompanyId' => $id])
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'companymaster.iCityId')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'companymaster.iStateId')
                ->first();
            $components = Component::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $Company->iCompanyId])->get();
            foreach ($components as $component) {
                $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iComponentId" => $component->iComponentId])
                    ->get();
                $component['subcomponent'] = $subcomponents;
            }
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $Company->iCompanyId])->distinct()->groupBy('strSystem')->get();

            $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $Company->iCompanyId])->get();
            $callcompetencies = CallCompetency::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $Company->iCompanyId])->get();
            $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $Company->iCompanyId])->get();
            $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $Company->iCompanyId])->get();

            //$infoTables = infoTable::where(["tableName" => "company", "iOemCompanyId" => $Company->iCompanyId])->orderBy('id', 'Desc')->limit(10)->get();
            $infoTables = infoTable::where(["iOemCompanyId" => $Company->iCompanyId])->orderBy('id', 'Desc')->limit(10)->get();
            //dd($infoTables);
             $moduleList=ModuleMaster::where('oem_company_modules.iOEMCompany',  $Company->iCompanyId)
                    ->join('oem_company_modules', 'module_masters.id', '=', 'oem_company_modules.iModuleId')
                    ->get();
            return view('admin.company.info', compact('Company', 'infoTables', 'components', 'systems', 'supporttypes', 'callcompetencies', 'issuetypes', 'resolutionCategories','moduleList'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $Company = [];
            if ($id != "") {
                $Company = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])
                    ->where('iCompanyId', "=", $id)
                    ->first();
            }
            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();

            return view('admin.company.add', compact('Company', 'statemasters', 'citymasters'));
        } else {
            return redirect()->route('home');
        }
    }

    public function companycomponentcreate($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $components = Component::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $id])->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $id])->get();
            return view('admin.company.addcomponent', compact('components', 'subcomponents', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function issuetypecreate($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $id])->get();
            return view('admin.company.issueType', compact('issuetypes'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callcompetencycreate($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $callcompetencies = CallCompetency::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $id])->get();
            return view('admin.company.callCompetency', compact('callcompetencies'));
        } else {
            return redirect()->route('home');
        }
    }

    public function supporttypecreate($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $id])->get();
            return view('admin.company.supporttype', compact('supporttypes'));
        } else {
            return redirect()->route('home');
        }
    }

    public function resolutioncategorycreate($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $id])->get();
            return view('admin.company.resolution_category', compact('resolutionCategories'));
        } else {
            return redirect()->route('home');
        }
    }

    public function create(Request $request)
    {
        // dd($request);
        if (Auth::User()->role_id == 1) {
            if ($request->company_id != 0 || $request->company_id == "") {
                $request->validate([
                    'EmailId'         => 'required|unique:companymaster,EmailId,' . $request->company_id . ',iCompanyId',
                    'ContactNo' => 'required',
                    'strOEMCompanyName'   => 'required|unique:companymaster,strOEMCompanyName,' . $request->company_id . ',iCompanyId',
                    //'strCompanyPrefix' => 'required|unique:companymaster,strCompanyPrefix,' . $request->company_id . ',iCompanyId',
                    'ContactPerson'   => 'required',
                    'Address1'      => 'required',
                    'Address2'      => 'required',
                    'Pincode'      => 'required',
                    'iStateId'     => 'required',
                    'iCityId'    => 'required',
                    'strGSTNo'  => 'required'
                ]);
            } else {
                $request->validate([
                    'EmailId'         => 'required|unique:companymaster,EmailId',
                    'ContactNo' => 'required',
                    'strOEMCompanyName'   => 'required|unique:companymaster,strOEMCompanyName',
                    'strCompanyPrefix' => 'required|unique:companymaster,strCompanyPrefix',
                    'ContactPerson'   => 'required',
                    'Address1'      => 'required',
                    'Address2'      => 'required',
                    'Pincode'      => 'required',
                    'iStateId'     => 'required',
                    'iCityId'    => 'required',
                    'strGSTNo'  => 'required'
                ]);
            }
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $Data = array(
                    'strOEMCompanyName' => $request->strOEMCompanyName,
                    'strCompanyPrefix' => $request->strCompanyPrefix,
                    'ContactPerson' => $request->ContactPerson,
                    'EmailId' => $request->EmailId,
                    'ContactNo' => $request->ContactNo,
                    'Address1' => $request->Address1,
                    'Address2' => $request->Address2,
                    'Address3' => $request->Address3,
                    'Pincode' => $request->Pincode,
                    'iStateId' => $request->iStateId,
                    'iCityId' => $request->iCityId,
                    'strGSTNo' => strtoupper($request->strGSTNo),
                    "iEntryBy" => $session,
                    "strEntryDate" => date('Y-m-d H:i:s'),
                    "strIP" => $request->ip()
                );

                if ($request->company_id != 0 || $request->company_id == "") {
                    // dd('if');
                    DB::table('companymaster')->where("iCompanyId", '=', $request->company_id)->update($Data);
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();

                    $companymaster = DB::table('companymaster')->where("iCompanyId", '=', $request->company_id)->first();
                    // dd($companymaster);

                    $name = explode(' ', $request->ContactPerson, 2);
                    $first = "";
                    $first = $name[0];

                    if (!empty($name[1])) {
                        $last = "";
                        $last = $name[1];
                    } else {
                        $last = "";
                    }
                    $arr = array(
                        'first_name'    => $first,
                        'last_name'     => $last,
                        'email'         => $request->EmailId,
                        'mobile_number' => $request->ContactPerson,
                    );
                    $User = User::where("id", '=', $companymaster->iUserId)->update($arr);

                    $infoArr = array(
                        'tableName'    => "company",
                        'tableAutoId'    => $request->company_id,
                        'iOemCompanyId'=>$request->company_id,
                        'tableMainField'  => "companyName",
                        'action'     => "Updated",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );

                    $Info = infoTable::create($infoArr);
                } else {
                    // dd('else');
                    $request->validate([
                        'EmailId'         => 'required|unique:companymaster,EmailId',
                        'ContactNo' => 'required',
                        'strOEMCompanyName'   => 'required|unique:companymaster,strOEMCompanyName',
                        'strCompanyPrefix' => 'required|unique:companymaster,strCompanyPrefix',
                        'ContactPerson'   => 'required',
                        'Address1'      => 'required',
                        'Address2'      => 'required',
                        'Pincode'      => 'required',
                        'iStateId'     => 'required',
                        'iCityId'    => 'required',
                        'strGSTNo'  => 'required'
                    ]);
                    $Company = DB::table('companymaster')->insertGetId($Data);

                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "company",
                        'tableAutoId'    => $Company,
                        'iOemCompanyId'=>$request->company_id,
                        'tableMainField'  => "companyName",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );

                    $Info = infoTable::create($infoArr);
                    $name = explode(' ', $request->ContactPerson, 2);
                    $first = "";
                    $first = $name[0];

                    if (!empty($name[1])) {
                        $last = "";
                        $last = $name[1];
                    } else {
                        $last = "";
                    }
                    $tempPass = $first . '@' . $request->ContactNo;
                    $arr = array(
                        'first_name'    => $first,
                        'last_name'     => $last,
                        'email'         => $request->EmailId,
                        'mobile_number' => $request->ContactNo,
                        'role_id'       => 2,
                        'status'        => 1,
                        'password'      => Hash::make($tempPass)
                    );
                    $User = User::create($arr);
                    $ORMcompanyID = $Company;
                    $ORMcompanyID = "WLP" . str_pad($ORMcompanyID, 5, "0", STR_PAD_LEFT);
                    DB::table('companymaster')->where("iCompanyId", '=', $Company)->update(["iUserId" => $User->id, "strOEMCompanyId" => $ORMcompanyID]);

                    $SendEmailDetails = DB::table('sendemaildetails')
                        ->where(['id' => 2])
                        ->first();
                    $msg = array(
                        'FromMail' => $SendEmailDetails->strFromMail,
                        'Title' => $SendEmailDetails->strTitle,
                        'ToEmail' => $request->EmailId,
                        'Subject' => $SendEmailDetails->strSubject
                    );
                    $companyInfo = CompanyInfo::where('iCompanyId', '=', '0')->first();
                    $companydata = array(
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
                    $mail = Mail::send('emails.comapnyAdd', ['user' => $User, 'password' => $tempPass, 'Company' => $request->strOEMCompanyName, 'data' => $companydata], function ($message) use ($msg) {
                        $message->from($msg['FromMail'], $msg['Title']);
                        $message->to($msg['ToEmail'])->subject($msg['Subject']);
                    });
                }
                // echo $request->save;
                //     dd($Data);
                DB::commit();
                $save = $request->save;
                //dd($save);
                Session::flash('Success', 'Company Created Successfully.');
                if ($save == '1') {
                    $company_id = 0;
                    if ($request->company_id != 0 || $request->company_id == "") {
                        $company_id = $request->company_id;
                    } else {
                        $company_id = $Company;
                    }
                    echo $company_id;
                } else {
                    return redirect()->route('company.index');
                }
            } catch (\Throwable $th) {
                // Rollback and return with Error
                DB::rollBack();
                Session::flash('Error', $th->getMessage());
                $save = $request->save;
                if ($save == '1') {
                    echo 0;
                } else {
                    return redirect()->back()->withInput();
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function editview(Request $request, $id)
    {
        $Data = CompanyMaster::where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyId' => $id])->first();
        return  json_encode($Data);
    }

    public function update(Request $request, $Id)
    {
        if (Auth::User()->role_id == 1) {
            $Student = DB::table('testimonial')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyId' => $request->iCompanyId])
                ->update([
                    'strOEMCompanyName' => $request->strOEMCompanyName,
                    'strOEMCompanyId' => $request->strOEMCompanyId,
                    'ContactPerson' => $request->ContactPerson,
                    'EmailId' => $request->EmailId,
                    'ContactNo' => $request->ContactNo,
                    'Address1' => $request->Address1,
                    'Address2' => $request->Address2,
                    'Address3' => $request->Address3,
                    'Pincode' => $request->Pincode,
                    'iStateId' => $request->iStateId,
                    'iCityId' => $request->iCityId,
                    'strGSTNo' => strtoupper($request->strGSTNo)
                ]);
            return redirect()->route('company.index')->with('Success', 'Company Updated Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if (Auth::User()->role_id == 1) {
            $delete = DB::table('testimonial')->where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyId' => $Id])->first();
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root . '/Testimonial/';
            unlink($destinationpath . $delete->photo);

            DB::table('testimonial')->where(['iStatus' => 1, 'isDelete' => 0, 'iCompanyId' => $Id])->delete();

            return redirect()->route('company.index')->with('Success', 'Company Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }

    public function getCity(Request $request)
    {
        $citymasters = DB::table('citymaster')
            ->where(['isDelete' => 0, "iStatus" => 1, 'iStateId' => $request->iStateId])
            ->orderBy('strCityName', 'ASC')
            ->get();
        $html = "";
        $html .= "<option label='Please Select' value=''>-- Select --</option>";
        foreach ($citymasters as $city) {
            $html .= '<option value="' . $city->iCityId . '">' . $city->strCityName . '</option>';
        }
        echo $html;
    }

    public function emailvalidate(Request $req)
    {
        // dd($request);
        $email = $req->email;
        if (isset($req->company_id) && $req->company_id != 0) {
            $CompanyMaster = CompanyMaster::where('iCompanyId', '=', $req->company_id)->first();
            $useremail = User::where("email", "LIKE", "\\" . $email . "%")->where("id", '!=', $CompanyMaster->iUserId)->count();
        } else {
            //$useremail = CompanyMaster::where("EmailId", "LIKE", "\\" . $email . "%")->count();
            $useremail = User::where("email", "LIKE", "\\" . $email . "%")->count();
        }
        if ($useremail > 0) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function OEMCompanyNamevalidate(Request $request)
    {

        $email = $request->strOEMCompanyName;
        if ($request->company_id == 0)
            $useremail = CompanyMaster::where("strOEMCompanyName", "LIKE", "\\" . $email . "%")->count();
        else
            $useremail = CompanyMaster::where("strOEMCompanyName", "LIKE", "\\" . $email . "%")->where('iCompanyId', '!=', $request->company_id)->count();

        if ($useremail > 0) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function resolutioncategorycreatestore(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                //DB::table('resolutioncategory')->where("iCompanyId" ,"=",$request->company_id)->delete();
                $id = array();
                foreach ($request->iResolutionCategoryId as $iResolutionCategoryId) {
                    array_push($id, $iResolutionCategoryId);
                }
                // $resolutioncategory = DB::table('resolutioncategory')->whereNotIn("iResolutionCategoryId", $id)->get();
                // dd($resolutioncategory);
                DB::table('resolutioncategory')->whereNotIn("iResolutionCategoryId", $id)->where("iCompanyId", '=', $request->company_id)->update(["isDelete" => 1]);
                $iCounter = 0;
                foreach ($request->field_name as $strResolutionCategory) {
                    $resolution_category = DB::table('resolutioncategory')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strResolutionCategory', 'like', trim($strResolutionCategory))->count();
                    $resolutionCategory = 0;
                    if ($resolution_category == 0) {
                        $Data = array(
                            'strResolutionCategory' => $strResolutionCategory,
                            "iCompanyId" => $request->company_id,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            "strIP" => $request->ip()
                        );

                        if ($request->iResolutionCategoryId[$iCounter] != 0) {
                            $resolutionCategory = $request->iResolutionCategoryId[$iCounter];
                            DB::table('resolutioncategory')->where("iResolutionCategoryId", "=", $request->iResolutionCategoryId[$iCounter])->update($Data);
                        } else {
                            $resolutionCategory = DB::table('resolutioncategory')->insertGetId($Data);
                        }
                    }

                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "resolutioncategory",
                        'tableAutoId'    => $resolutionCategory,
                        'iOemCompanyId'=>$request->company_id,
                        'tableMainField'  => "resolutionCategory",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                DB::commit();
                $save = $request->save;
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('company.index')->with('Success', 'Company Solution Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->company_id;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function issuetypestore(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $id = array();
                foreach ($request->iSSueTypeId as $iSSueTypeId) {
                    array_push($id, $iSSueTypeId);
                }
                DB::table('issuetype')->whereNotIn("iSSueTypeId", $id)->where("iCompanyId", '=', $request->company_id)->update(["isDelete" => 1]);

                $iCounter = 0;
                foreach ($request->field_name as $strIssueType) {
                    $issue_type = DB::table('issuetype')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strIssueType', 'like', trim($strIssueType))->count();
                    $issuetype = 0;
                    if ($issue_type == 0) {
                        $Data = array(
                            'strIssueType' => $strIssueType,
                            "iCompanyId" => $request->company_id,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            "strIP" => $request->ip()
                        );

                        if ($request->iSSueTypeId[$iCounter] != 0) {
                            $issuetype = $request->iSSueTypeId[$iCounter];
                            DB::table('issuetype')->where("iSSueTypeId", "=", $request->iSSueTypeId[$iCounter])->update($Data);
                        } else {
                            $issuetype = DB::table('issuetype')->insertGetId($Data);
                        }
                    }

                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "issuetype",
                        'tableAutoId'    => $issuetype,
                        'iOemCompanyId'=>$request->company_id,
                        'tableMainField'  => "IssueType",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                DB::commit();
                $save = $request->save;
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('company.index')->with('Success', 'Company Issue Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->company_id;;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function callcompetencystore(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $id = array();
                foreach ($request->iCallCompetency as $iCallCompetency) {
                    array_push($id, $iCallCompetency);
                }
                DB::table('callcompetency')->whereNotIn("iCallCompetency", $id)->where("iCompanyId", '=', $request->company_id)->update(["isDelete" => 1]);

                $iCounter = 0;
                foreach ($request->field_name as $strCallCompetency) {
                    $call_competency = DB::table('callcompetency')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strCallCompetency', 'like', trim($strCallCompetency))->count();
                    $callcompetency = 0;
                    if ($call_competency == 0) {
                        if ($strCallCompetency != "") {
                            $Data = array(
                                'strCallCompetency' => $strCallCompetency,
                                "iCompanyId" => $request->company_id,
                                "strEntryDate" => date('Y-m-d H:i:s'),
                                "strIP" => $request->ip()
                            );
                        }

                        if ($request->iCallCompetency[$iCounter] != 0) {
                            $callcompetency = $request->iCallCompetency[$iCounter];
                            if ($strCallCompetency == "") {
                                DB::table('callcompetency')->where("iCallCompetency", "=", $request->iCallCompetency[$iCounter])->delete();
                            } else {
                                DB::table('callcompetency')->where("iCallCompetency", "=", $request->iCallCompetency[$iCounter])->update($Data);
                            }
                        } else {
                            $callcompetency = DB::table('callcompetency')->insertGetId($Data);
                        }
                    }
                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "callcompetency",
                        'tableAutoId'    => $callcompetency,
                        'iOemCompanyId'=>$request->company_id,
                        'tableMainField'  => "CallCompetency",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                DB::commit();
                $save = $request->save;
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('company.index')->with('Success', 'Company Issue Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->company_id;;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function supporttypestore(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $id = array();
                foreach ($request->iSuppotTypeId as $iSuppotTypeId) {
                    array_push($id, $iSuppotTypeId);
                }
                DB::table('supporttype')->whereNotIn("iSuppotTypeId", $id)->where("iCompanyId", '=', $request->company_id)->update(["isDelete" => 1]);

                $iCounter = 0;
                foreach ($request->field_name as $strSupportType) {
                    $support_type = DB::table('supporttype')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strSupportType', 'like', trim($strSupportType))->count();

                    $supporttype = 0;

                    if ($strSupportType) {
                        if ($support_type == 0) {
                            $Data = array(
                                'strSupportType' => $strSupportType,
                                "iCompanyId" => $request->company_id,
                                "strEntryDate" => date('Y-m-d H:i:s'),
                                "strIP" => $request->ip()
                            );
                            if ($request->iSuppotTypeId[$iCounter] != 0) {
                                $supporttype = $request->iSuppotTypeId[$iCounter];
                                if ($strSupportType == "") {
                                    DB::table('supporttype')->where("iSuppotTypeId", "=", $request->iSuppotTypeId[$iCounter])->delete();
                                } else {
                                    DB::table('supporttype')->where("iSuppotTypeId", "=", $request->iSuppotTypeId[$iCounter])->update($Data);
                                }
                            } else {
                                $supporttype = DB::table('supporttype')->insertGetId($Data);
                            }
                        }

                        $iCounter++;
                        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                        $userdata = \App\Models\User::whereId($session)->first();
                        $infoArr = array(
                            'tableName'    => "supporttype",
                            'tableAutoId'    => $supporttype,
                            'iOemCompanyId'=>$request->company_id,
                            'tableMainField'  => "SupportType",
                            'action'     => "Insert",
                            'strEntryDate' => date("Y-m-d H:i:s"),
                            'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                        );
                        $Info = infoTable::create($infoArr);
                    }
                }
                DB::commit();
                $save = $request->save;
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('company.index')->with('Success', 'Company Issue Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->company_id;;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function updateStatus(Request $request)
    {
        if ($request->status == 1) {
            //User::where(['id' => $request->UserId])->update(['status' => 0]);
            CompanyMaster::where(['isDelete' => 0, 'iCompanyId' => $request->iCompanyId])->update(['iStatus' => 0]);
        } else {
            //User::where(['id' => $request->UserId])->update(['status' => 1]);
            CompanyMaster::where(['isDelete' => 0, 'iCompanyId' => $request->iCompanyId])->update(['iStatus' => 1]);
        }
        echo 1;
    }

    public function companycomponentstore(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $iCounter = 0;
                foreach ($request->strSystem as $strSystem) {

                    $system = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strSystem', 'like', trim($strSystem))->count();
                    $iSystemId = 0;
                    if (isset($system) && $system == 1) {
                        $systemId = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                            ->where('strSystem', 'like', trim($strSystem))->first();
                        $iSystemId = $systemId->iSystemId;
                    } else {
                        $SystemArr = array(
                            "iCompanyId" => $request->company_id,
                            "strSystem" => trim($strSystem),
                            "strIP" => $request->ip()
                        );
                        $SystemId = System::create($SystemArr);
                        $iSystemId = $SystemId->id;
                    }
                    $componentArr = array(
                        "iCompanyId" => $request->company_id,
                        "strSystem" => $iSystemId,
                        "strComponent" => $request->strComponent[$iCounter],
                        "IsSubComponent" => $request->IsSubComponent[$iCounter],
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    $ComponentId = Component::create($componentArr);
                    $iComponentId = $ComponentId->id;

                    foreach ($request->strSubComponent[$iCounter] as $strSubComponent) {
                        if ($strSubComponent != "") {
                            $SubComponent = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                                ->where('strSubComponent', 'like', trim($strSubComponent))->count();
                            if ($SubComponent == 0) {
                                $subcomponentArr = array(
                                    "iCompanyId" => $request->company_id,
                                    "iComponentId" => $iComponentId,
                                    "strSubComponent" => $strSubComponent,
                                    "strEntryDate" => date('Y-m-d H:i:s'),
                                    "strIP" => $request->ip()
                                );
                                $iSubComponentId = SubComponent::create($subcomponentArr);
                            }
                        }
                    }
                    $iCounter++;

                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "component",
                        'tableAutoId'    => $iComponentId,
                        'iOemCompanyId'=>$request->company_id,
                        'tableMainField'  => "Component Insert",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                $save = $request->save;
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('company.index')->with('Success', 'Company Component Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->company_id;;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function componentslist(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            $components = Component::select('companymaster.strOEMCompanyName', 'system.strSystem', 'component.iCompanyId', 'strComponent', 'IsSubComponent', 'iComponentId')
                ->join('companymaster', 'companymaster.iCompanyId', '=', 'component.iCompanyId')
                ->join('system', 'system.iSystemId', '=', 'component.strSystem')
                ->where(['component.isDelete' => 0, 'component.iStatus' => 1])
                ->when($request->search_company, fn($query, $search_company) => $query->where('component.iCompanyId', $search_company))
                ->when($request->search_system, fn($query, $search_system) => $query->where('component.strSystem', $search_system))
                ->when($request->search_component, fn($query, $search_component) => $query->where('iComponentId', $search_component))
                ->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1])
                ->get();
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1])->distinct()->orderBy('strSystem', 'ASC')->get();
            return view('admin.company.componentslist', compact('components', 'subcomponents', 'CompanyMaster', 'systems', 'componentLists'));
        } else {
            return redirect()->route('home');
        }
    }

    public function  getsystem(Request $request)
    {
        $systems = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->search_company])->get();
        $html = "";
        $html .= '<option label="Please Select" value="">-- Select --</option>';
        foreach ($systems as $system) {
            $html .= '<option value="' . $system->iSystemId . '">' . $system->strSystem . '</option>';
        }
        echo $html;
    }

    public function getcomponent(Request $request)
    {
        $componentLists = Component::where(['isDelete' => 0, 'iStatus' => 1, "strSystem" => $request->search_system])
            ->orderBy('strComponent', 'ASC')
            ->get();


        $html = "";
        if (count($componentLists) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($componentLists as $component) {
                $html .= '<option value="' . $component->iComponentId . '">' . $component->strComponent . '</option>';
            }
        } else {
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    public function componentsedit($id)
    {
        if (Auth::User()->role_id == 1) {
            $components = Component::select('component.*', 'system.strSystem as system')
                ->join('system', 'system.iSystemId', '=', 'component.strSystem')
                ->where(['component.isDelete' => 0, 'component.iStatus' => 1, 'iComponentId' => $id])
                ->first();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iComponentId" => $id])->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => $components->iCompanyId])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            return view('admin.company.componentedit', compact('components', 'subcomponents', 'CompanyMaster'));
        } else {
            return redirect()->route('home');
        }
    }

    public function companycomponentupdate(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $iCounter = 0;

                $system = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                    ->where('strSystem', 'like', trim($request->strSystem))->count();
                $iSystemId = 0;
                if (isset($system) && $system == 1) {
                    $systemId = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strSystem', 'like', trim($request->strSystem))->first();
                    $iSystemId = $systemId->iSystemId;
                } else {
                    $SystemArr = array(
                        "iCompanyId" => $request->company_id,
                        "strSystem" => trim($request->strSystem),
                        "strIP" => $request->ip()
                    );
                    $SystemId = System::create($SystemArr);
                    $iSystemId = $SystemId->id;
                }
                $componentArr = array(
                    "iCompanyId" => $request->company_id,
                    "strSystem" => $iSystemId,
                    "strComponent" => $request->strComponent,
                    "IsSubComponent" => $request->IsSubComponent,
                    "strEntryDate" => date('Y-m-d H:i:s'),
                    "strIP" => $request->ip()
                );
                $ComponentId = Component::where(["iComponentId" => $request->iComponentId])->update($componentArr);
                $iComponentId = $request->iComponentId;

                $id = array();
                foreach ($request->iSubComponentId as $iSubComponentId) {
                    array_push($id, $iSubComponentId);
                }
                DB::table('subcomponent')->whereNotIn("iSubComponentId", $id)
                    ->where("iCompanyId", '=', $request->company_id)
                    ->where("iComponentId", '=', $iComponentId)
                    ->delete();
                $iCounter = 0;
                foreach ($request->iSubComponentId as $iSubComponentId) {
                    if ($request->strSubComponent[$iCounter] != "") {
                        $subcomponent = DB::table('subcomponent')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                            ->where('strSubComponent', 'like', trim($request->strSubComponent[$iCounter]))->count();
                        if ($subcomponent == 0) {
                            $subcomponentArr = array(
                                "iCompanyId" => $request->company_id,
                                "iComponentId" => $iComponentId,
                                "strSubComponent" => $request->strSubComponent[$iCounter],
                                "strEntryDate" => date('Y-m-d H:i:s'),
                                "strIP" => $request->ip()
                            );
                            if ($iSubComponentId != 0) {
                                DB::table('subcomponent')->where("iSubComponentId", "=", $iSubComponentId)->update($subcomponentArr);
                            } else {
                                DB::table('subcomponent')->insertGetId($subcomponentArr);
                            }
                        }
                    }
                    $iCounter++;
                }

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "component",
                    'tableAutoId'    => $iComponentId,
                    'iOemCompanyId'=>$request->company_id,
                    'tableMainField'  => "Component Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);

                return redirect()->route('company.componentslist')->with('Success', 'Company Components Edited Successfully.');
            } catch (\Throwable $th) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('Error', $th->getMessage());
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function getCompany(Request $request)
    {
        //dd($request);

        $CompanyClient = CompanyClient::orderBy('CompanyName', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iCompanyId])->get();
        $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $request->iCompanyId])->orderBy('strCompanyClientProfile', 'ASC')->get();
        $Distributor = Distributor::orderBy('Name', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iCompanyId])->get();
        $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iCompanyId])->distinct()->groupBy('strSystem')->orderBy('strSystem', 'ASC')->get();

        $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strSupportType', 'ASC')->get();
        $callcompetencies = CallCompetency::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strCallCompetency', 'ASC')->get();
        $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strIssueType', 'ASC')->get();
        $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strResolutionCategory', 'ASC')->get();
        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1, "iExecutiveLevel" => 2, "iOEMCompany" => $request->iCompanyId])
            ->where('iUserId', '!=', Auth::User()->id)
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["status" => 1])->whereIn("role_id", [3, 2])->where('id', '!=', Auth::User()->id);
            })->orderBY('strFirstName', 'ASC')->get();

        $clientHTML = '<option label="Please Select" value=""> -- Select --</option>';
        if (count($CompanyClient) > 0) {
            foreach ($CompanyClient as $clients) {
                $clientHTML .= '<option value="' . $clients->iCompanyClientId . '">' . $clients->CompanyName . '</option>';
            }
            $clientHTML .= '<option value="Other"> Other</option>';
        } else {
            $clientHTML .= '<option value="Other"> Other</option>';
        }

        $clientProfileHTML = '<option label="Please Select" value=""> -- Select --</option>';
        if (count($CompanyClientProfile) > 0) {
            foreach ($CompanyClientProfile as $clientsPro) {
                $clientProfileHTML .= '<option value="' . $clientsPro->iCompanyClientProfileId . '">' . $clientsPro->strCompanyClientProfile . '</option>';
            }
            $clientProfileHTML .= '<option value="Other"> Other</option>';
        } else {
            $clientProfileHTML .= '<option value="Other"> Other</option>';
        }
        $distributorHTML = '';
        if (count($Distributor) > 0) {
            $distributorHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($Distributor as $dis) {
                $distributorHTML .= '<option value="' . $dis->iDistributorId . '">' . $dis->Name . '</option>';
            }
        } else {
            $distributorHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $sysytemHTML = '';
        if (count($systems) > 0) {
            $sysytemHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($systems as $system) {
                $sysytemHTML .= '<option value="' . $system->iSystemId . '">' . $system->strSystem . '</option>';
            }
        } else {
            $sysytemHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $supprttypeHTML = '';
        if (count($supporttypes) > 0) {
            $supprttypeHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($supporttypes as $supporttype) {
                $supprttypeHTML .= '<option value="' . $supporttype->iSuppotTypeId . '">' . $supporttype->strSupportType . '</option>';
            }
        } else {
            $supprttypeHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $callcompetencyHTML = '';
        if (count($callcompetencies) > 0) {
            $callcompetencyHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($callcompetencies as $callcompetency) {
                $callcompetencyHTML .= '<option value="' . $callcompetency->iCallCompetency . '">' . $callcompetency->strCallCompetency . '</option>';
            }
        } else {
            $callcompetencyHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $issuetypeHTML = '';
        if (count($issuetypes) > 0) {
            $issuetypeHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($issuetypes as $issuetype) {
                $issuetypeHTML .= '<option value="' . $issuetype->iSSueTypeId . '">' . $issuetype->strIssueType . '</option>';
            }
        } else {
            $issuetypeHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $resolutioncategoryHTML = '';
        if (count($resolutionCategories) > 0) {
            $resolutioncategoryHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($resolutionCategories as $resolutionCategory) {
                $resolutioncategoryHTML .= '<option value="' . $resolutionCategory->iResolutionCategoryId . '">' . $resolutionCategory->strResolutionCategory . '</option>';
            }
        } else {
            $resolutioncategoryHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $exeListHTML = '';
        if (count($executiveList) > 0) {
            $exeListHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($executiveList as $exe) {
                $exeListHTML .= '<option value="' . $exe->iCallAttendentId . '">' . $exe->strFirstName . ' ' . $exe->strLastName . '</option>';
            }
        } else {
            $exeListHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $data = array('client' => $clientHTML, "profile" => $clientProfileHTML, "distributor" => $distributorHTML, "system" => $sysytemHTML,  "supporttype" => $supprttypeHTML, "callcompetency" => $callcompetencyHTML, "issuetype" => $issuetypeHTML, "resolutionCategory" => $resolutioncategoryHTML, "exeList" => $exeListHTML);
        return (json_encode($data));
    }

    public function geteditCompany(Request $request)
    {
        //dd($request);

        $CompanyClient = CompanyClient::orderBy('CompanyName', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iCompanyId])->get();
        $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $request->iCompanyId])->orderBy('strCompanyClientProfile', 'ASC')->get();
        $Distributor = Distributor::orderBy('Name', 'ASC')->where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iCompanyId])->get();
        $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $request->iCompanyId])->distinct()->groupBy('strSystem')->orderBy('strSystem', 'ASC')->get();

        $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strSupportType', 'ASC')->get();
        $callcompetencies = CallCompetency::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strCallCompetency', 'ASC')->get();
        $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strIssueType', 'ASC')->get();
        $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->iCompanyId])->orderBY('strResolutionCategory', 'ASC')->get();
        $executiveList = CallAttendent::where(["isDelete" => 0, "iStatus" => 1, "iExecutiveLevel" => 2, "iOEMCompany" => $request->iCompanyId])
            ->where('iUserId', '!=', Auth::User()->id)
            ->whereIn('iUserId', function ($query) {
                $query->select('id')->from('users')->where(["role_id" => 3, "status" => 1]);
            })->orderBY('strFirstName', 'ASC')->get();
        $statemasters = DB::table('statemaster')
            ->where(['isDelete' => 0, "iStatus" => 1])
            ->orderBy('strStateName', 'ASC')
            ->get();
        $citymasters = DB::table('citymaster')
            ->where(['isDelete' => 0, "iStatus" => 1])
            ->orderBy('strCityName', 'ASC')
            ->get();
        $componentHTML = '';
        $subcomponentHTML = "";
        $ticketmaster = DB::table('ticketmaster')->where("iTicketId", "=", $request->iTicketId)
            ->first();

        $ticketmaster = DB::table('ticketmaster')->where("iTicketId", "=", $request->iTicketId)
            ->first();
        $array = explode(",", $ticketmaster->iSubComponentId);
        //dd($array);
        $Components = Component::where(["strSystem" => $ticketmaster->iSystemId, 'iCompanyId' => $request->iCompanyId])->get();
        if (count($Components) > 0) {
            $componentHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($Components as $Component) {
                if ($ticketmaster->iComnentId == $Component->iComponentId) {
                    $componentHTML .= '<option value="' . $Component->iComponentId . '" selected>' . $Component->strComponent . '</option>';
                } else {
                    $componentHTML .= '<option value="' . $Component->iComponentId . '" >' . $Component->strComponent . '</option>';
                }
            }
        } else {
            //$componentHTML = '<option label="Please Select" value=""> -- Select --</option>';
            $componentHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iComponentId" => $ticketmaster->iComnentId, "iCompanyId" => $request->iCompanyId])
            ->orderBy('strSubComponent', 'ASC')
            ->get();
        if (count($subcomponents) > 0) {
            $subcomponentHTML .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($subcomponents as $subcomponent) {
                if (in_array($subcomponent->iSubComponentId, $array)) {

                    $subcomponentHTML .= '<option value="' . $subcomponent->iSubComponentId . '" selected >' . $subcomponent->strSubComponent . '</option>';
                } else {
                    $subcomponentHTML .= '<option value="' . $subcomponent->iSubComponentId . '">' . $subcomponent->strSubComponent . '</option>';
                }
            }
        } else {
            $subcomponentHTML .= '<option label="Please Select" value="" selected>No record Found</option>';
        }


        $clientHTML = '<option label="Please Select" value=""> -- Select --</option>';
        if (count($CompanyClient) > 0) {
            foreach ($CompanyClient as $clients) {
                if ($ticketmaster->iCompanyId == $clients->iCompanyClientId) {
                    $clientHTML .= '<option value="' . $clients->iCompanyClientId . '" selected>' . $clients->CompanyName . '</option>';
                } else {
                    $clientHTML .= '<option value="' . $clients->iCompanyClientId . '">' . $clients->CompanyName . '</option>';
                }
            }
            $clientHTML .= '<option value="Other"> Other</option>';
        } else {
            $clientHTML .= '<option value="Other"> Other</option>';
        }
        $clientProfileHTML = '<option label="Please Select" value=""> -- Select --</option>';
        if (count($CompanyClientProfile) > 0) {
            foreach ($CompanyClientProfile as $clientsPro) {
                if ($ticketmaster->iCompanyProfileId == $clientsPro->iCompanyClientProfileId) {
                    $clientProfileHTML .= '<option value="' . $clientsPro->iCompanyClientProfileId . '" selected>' . $clientsPro->strCompanyClientProfile . '</option>';
                } else {
                    $clientProfileHTML .= '<option value="' . $clientsPro->iCompanyClientProfileId . '">' . $clientsPro->strCompanyClientProfile . '</option>';
                }
            }
            $clientProfileHTML .= '<option value="Other"> Other</option>';
        } else {
            $clientProfileHTML .= '<option value="Other"> Other</option>';
        }
        $distributorHTML = '';
        if (count($Distributor) > 0) {
            $distributorHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($Distributor as $dis) {
                if ($ticketmaster->iDistributorId == $dis->iDistributorId) {
                    $distributorHTML .= '<option value="' . $dis->iDistributorId . '" selected>' . $dis->Name . '</option>';
                } else {
                    $distributorHTML .= '<option value="' . $dis->iDistributorId . '">' . $dis->Name . '</option>';
                }
            }
        } else {
            $distributorHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $sysytemHTML = '';
        if (count($systems) > 0) {
            $sysytemHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($systems as $system) {
                if ($ticketmaster->iSystemId == $system->iSystemId) {
                    $sysytemHTML .= '<option value="' . $system->iSystemId . '" selected>' . $system->strSystem . '</option>';
                } else {
                    $sysytemHTML .= '<option value="' . $system->iSystemId . '">' . $system->strSystem . '</option>';
                }
            }
        } else {
            $sysytemHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $supprttypeHTML = '';
        if (count($supporttypes) > 0) {
            $supprttypeHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($supporttypes as $supporttype) {
                if ($ticketmaster->iSupportType == $supporttype->iSuppotTypeId) {
                    $supprttypeHTML .= '<option value="' . $supporttype->iSuppotTypeId . '" selected>' . $supporttype->strSupportType . '</option>';
                } else {
                    $supprttypeHTML .= '<option value="' . $supporttype->iSuppotTypeId . '">' . $supporttype->strSupportType . '</option>';
                }
            }
        } else {
            $supprttypeHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $callcompetencyHTML = '';
        if (count($callcompetencies) > 0) {
            $callcompetencyHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($callcompetencies as $callcompetency) {
                if ($ticketmaster->CallerCompetencyId == $callcompetency->iCallCompetency) {
                    $callcompetencyHTML .= '<option value="' . $callcompetency->iCallCompetency . '" selected>' . $callcompetency->strCallCompetency . '</option>';
                } else {
                    $callcompetencyHTML .= '<option value="' . $callcompetency->iCallCompetency . '">' . $callcompetency->strCallCompetency . '</option>';
                }
            }
        } else {
            $callcompetencyHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $issuetypeHTML = '';
        if (count($issuetypes) > 0) {
            $issuetypeHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($issuetypes as $issuetype) {
                if ($ticketmaster->iIssueTypeId == $issuetype->iSSueTypeId) {
                    $issuetypeHTML .= '<option value="' . $issuetype->iSSueTypeId . '" selected>' . $issuetype->strIssueType . '</option>';
                } else {
                    $issuetypeHTML .= '<option value="' . $issuetype->iSSueTypeId . '">' . $issuetype->strIssueType . '</option>';
                }
            }
        } else {
            $issuetypeHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }
        $resolutioncategoryHTML = '';
        if (count($resolutionCategories) > 0) {
            $resolutioncategoryHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($resolutionCategories as $resolutionCategory) {
                if ($ticketmaster->iResolutionCategoryId == $resolutionCategory->iResolutionCategoryId) {
                    $resolutioncategoryHTML .= '<option value="' . $resolutionCategory->iResolutionCategoryId . '" selected>' . $resolutionCategory->strResolutionCategory . '</option>';
                } else {
                    $resolutioncategoryHTML .= '<option value="' . $resolutionCategory->iResolutionCategoryId . '">' . $resolutionCategory->strResolutionCategory . '</option>';
                }
            }
        } else {
            $resolutioncategoryHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $exeListHTML = '';
        if (count($executiveList) > 0) {
            $exeListHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($executiveList as $exe) {
                if ($ticketmaster->iLevel2CallAttendentId == $exe->iCallAttendentId) {
                    $exeListHTML .= '<option value="' . $exe->iCallAttendentId . '" selected>' . $exe->strFirstName . ' ' . $exe->strLastName . '</option>';
                } else {
                    $exeListHTML .= '<option value="' . $exe->iCallAttendentId . '">' . $exe->strFirstName . ' ' . $exe->strLastName . '</option>';
                }
            }
        } else {
            $exeListHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $stateListHTML = '';
        if (count($statemasters) > 0) {
            $stateListHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($statemasters as $state) {
                if ($ticketmaster->iStateId == $state->iStateId) {
                    $stateListHTML .= '<option value="' . $state->iStateId . '" selected>' . $state->strStateName . '</option>';
                } else {
                    $stateListHTML .= '<option value="' . $state->iStateId . '">' . $state->strStateName . '</option>';
                }
            }
        } else {
            $stateListHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $cityListHTML = '';
        if (count($citymasters) > 0) {
            $cityListHTML = '<option label="Please Select" value=""> -- Select --</option>';
            foreach ($citymasters as $city) {
                if ($ticketmaster->iCityId == $city->iCityId) {
                    $cityListHTML .= '<option value="' . $city->iCityId . '" selected>' . $city->strCityName . '</option>';
                } else {
                    $cityListHTML .= '<option value="' . $city->iCityId . '">' . $city->strCityName . '</option>';
                }
            }
        } else {
            $cityListHTML = '<option label="Please Select" value="" selected>No Record Found</option>';
        }

        $data = array('client' => $clientHTML, "profile" => $clientProfileHTML, "distributor" => $distributorHTML, "system" => $sysytemHTML,  "supporttype" => $supprttypeHTML, "callcompetency" => $callcompetencyHTML, "issuetype" => $issuetypeHTML, "resolutionCategory" => $resolutioncategoryHTML, "exeList" => $exeListHTML, "stateList" => $stateListHTML, "cityList" => $cityListHTML, "component" => $componentHTML, "subcomponent" => $subcomponentHTML);
        return (json_encode($data));
    }

    public function getCompanyClientEmail(Request $request)
    {
        if (isset($request->iCompanyId) && $request->iCompanyId != "") {
            $CompanyClient = CompanyClient::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyClientId' => $request->iCompanyId])->first();
            return ($CompanyClient->email);
        } else {
            return ("");
        }
    }

    public function migration(Request $request)
    {
        $companies = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1])->get();
        foreach ($companies as $company) {
            // $ticketmaster = DB::table('ticketmaster')->where("OemCompannyId", "=", $company->iCompanyId)
            //     ->update([
            //         "iOEMTicketId" => DB::raw("ROW_NUMBER() OVER (PARTITION BY OemCompannyId ORDER BY iTicketId)"),
            //         "strTicketUniqueID" => DB::raw('CONCAT("'.$company->strCompanyPrefix.'", LPAD("ticketmaster.iTicketId", 5, 0))')
            //     ]);
            /*
            "
                UPDATE ticketmaster AS t1
                JOIN (
                SELECT
                    iTicketId,
                    ROW_NUMBER() OVER (PARTITION BY OemCompannyId ORDER BY iTicketId) AS new_iOEMTicketId,
                    CONCAT('{$company->strCompanyPrefix}', LPAD(ROW_NUMBER() OVER (PARTITION BY OemCompannyId ORDER BY iTicketId), 5, '0')) AS new_strTicketUniqueID
                FROM ticketmaster
            ) AS t2
            ON t1.iTicketId = t2.iTicketId
            SET
                t1.iOEMTicketId = t2.new_iOEMTicketId,
                t1.strTicketUniqueID = t2.new_strTicketUniqueID
            WHERE t1.OemCompannyId = {$company->iCompanyId}"
            */
            DB::statement("
                UPDATE ticketmaster AS t1
                JOIN (
                    SELECT
                        iTicketId,
                        ROW_NUMBER() OVER (PARTITION BY OemCompannyId ORDER BY iTicketId) AS new_iOEMTicketId,
                        CONCAT('{$company->strCompanyPrefix}', LPAD(iTicketId, 5, '0')) AS new_strTicketUniqueID
                    FROM ticketmaster
                ) AS t2
                ON t1.iTicketId = t2.iTicketId
                SET
                    t1.iOEMTicketId = t2.iTicketId,
                    t1.strTicketUniqueID = t2.new_strTicketUniqueID
                WHERE t1.OemCompannyId = {$company->iCompanyId};
            ");
            /*DB::statement("SET @row_number = 0;");

            // Execute the UPDATE statement separately
            DB::statement("
                UPDATE ticketmaster AS t1
                JOIN (
                    SELECT
                        iTicketId,
                        (@row_number := @row_number + 1) AS new_iOEMTicketId,
                        CONCAT('{$company->strCompanyPrefix}', LPAD(iTicketId, 5, '0')) AS new_strTicketUniqueID
                    FROM ticketmaster
                    WHERE OemCompannyId = {$company->iCompanyId}
                    ORDER BY iTicketId
                ) AS t2
                ON t1.iTicketId = t2.iTicketId
                SET
                    t1.iOEMTicketId = t2.iTicketId,
                    t1.strTicketUniqueID = t2.new_strTicketUniqueID
                WHERE t1.OemCompannyId = {$company->iCompanyId};
            ");*/
            // t1.iOEMTicketId = t2.new_iOEMTicketId,
            return back()->with('Success', 'Migration Done Successfully.');;
        }
    }
    
    public function allowmodulecreate($id = null)
    {
        if (Auth::User()->role_id == 1) {
            $oemCompanyModules = OemCompanyModule::where(["iOEMCompany" => $id])->get()->toArray();
            $moduleMasters = ModuleMaster::get();
            
            return view('admin.company.allowmodule', compact('oemCompanyModules','moduleMasters'));
        } else {
            return redirect()->route('home');
        }
    }
    
    public function allowmodulestore(Request $request)
    {
        if (Auth::User()->role_id == 1) {
            try {
                $iCounter = 0;
                OemCompanyModule::where('iOEMCompany',$request->company_id)->delete();
                //dd($request);
                foreach($request->iModuleId as $iModuleId){
                    if($request->module[$iCounter] == 1){
                        $oemCompanyModule = new OemCompanyModule;
                        $oemCompanyModule->iOEMCompany = $request->company_id;
                        $oemCompanyModule->iModuleId = $iModuleId;
                        $oemCompanyModule->save();
                        
                        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                        $userdata = \App\Models\User::whereId($session)->first();
                        $infoArr = array(
                            'tableName'    => "oem_company_modules",
                            'tableAutoId'    => $request->company_id,
                            'iOemCompanyId' =>   $request->company_id,
                            'tableMainField'  => "OEMCompanyModules",
                            'action'     => "Insert",
                            'strEntryDate' => date("Y-m-d H:i:s"),
                            'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                        );
                       
                        $Info = infoTable::create($infoArr);
                    }
                    $iCounter++;
                    
                }
                
                
                DB::commit();
                $save = $request->save;
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('company.index')->with('Success', 'Module Allowed Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->company_id;;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }
    
}
