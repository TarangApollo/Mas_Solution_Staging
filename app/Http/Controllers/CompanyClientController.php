<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyClient;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyClientProfile;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\User;
use App\Models\infoTable;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompanyClientController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->role_id == 2){
            $search_name = $request->search_name;
            $search_email = $request->search_email;
            $search_state = $request->search_state;
            $search_city = $request->search_city;
            $daterange = $request->daterange;
            $search_daterange=$request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                 $formDate = date('Y-m-d', strtotime($daterange[0]));
                 $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $CompanyClients = CompanyClient::select('companyclient.*', 'strStateName', 'strCityName', 'strCompanyClientProfile','users.first_name','users.last_name')
                ->join('icompanyclientprofile', 'icompanyclientprofile.iCompanyClientProfileId', '=', 'companyclient.iCompanyClientProfileId', 'left outer')
                ->join('statemaster', 'statemaster.iStateId', '=', 'companyclient.iStateId', ' left outer')
                ->join('citymaster', 'citymaster.iCityId', '=', 'companyclient.iCityId', ' left outer')
                ->join('users', 'users.id', '=', 'companyclient.iEntryBy','left outer')
                ->when($request->search_name, fn ($query, $search_name) => $query->where('companyclient.CompanyName', $search_name))
                ->when($request->search_email, fn ($query, $search_email) => $query->where('companyclient.email', $search_email))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('companyclient.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('companyclient.iCityId', $search_city))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('companyclient.strEntryDate', [$formDate, $toDate]))
                ->orderBy('iCompanyClientId', 'DESC')->where(['companyclient.isDelete' => 0, 'companyclient.iCompanyId' => Session::get('CompanyId')])->get();

            $user = User::where(["id" => $session])->first();
            $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => Session::get('CompanyId')])->get();
            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();

            return view('wladmin.companyclient.index', compact('CompanyClients', 'user', 'CompanyClientProfile', 'statemasters', 'citymasters', 'search_name', 'search_email', 'search_state', 'search_city', 'search_daterange'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            //$CompanyClient = CompanyClient::orderBy('iCompanyClientId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1,'iCompanyId'=> $CompanyMaster->iCompanyId])->get();
            $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $CompanyMaster->iCompanyId])->get();

            return view('wladmin.companyclient.add', compact('CompanyClientProfile', 'CompanyMaster'));
        } else {
            return redirect()->route('home');
        }
    }

    public function basicinfocreate($id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $CompanyMaster->iCompanyId, "type" => 1])->get();
            if ($id != 0)
                $CompanyClient =  CompanyClient::where(['companyclient.isDelete' => 0, 'iCompanyClientId' => $id])->first();
            else
                $CompanyClient = null;

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            return view('wladmin.companyclient.addbasicinfo', compact('CompanyMaster', 'CompanyClientProfile', 'statemasters', 'citymasters', 'id', 'CompanyClient'));
        } else {
            return redirect()->route('home');
        }
    }

    public function create(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $id = array();
                foreach ($request->iCompanyClientProfileId as $iCompanyClientProfileId) {
                    array_push($id, $iCompanyClientProfileId);
                }
                DB::table('icompanyclientprofile')->whereNotIn("iCompanyClientProfileId", $id)->where("icompanyId", '=', $request->company_id)->update(["isDelete" => 1]);

                $iCounter = 0;
                foreach ($request->field_name as $strCompanyClientProfile) {
                    $Data = array(
                        'strCompanyClientProfile' => $strCompanyClientProfile,
                        'type' => $request->type[$iCounter],
                        "icompanyId" => $request->company_id,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    if ($request->iCompanyClientProfileId[$iCounter] != 0) {
                        DB::table('icompanyclientprofile')->where("iCompanyClientProfileId", "=", $request->iCompanyClientProfileId[$iCounter])->update($Data);
                    } else {
                        DB::table('icompanyclientprofile')->insertGetId($Data);
                    }
                    $iCounter++;
                }
                DB::commit();
                $save = $request->save;
                Session::flash('Success', 'Company Client Profile Created Successfully.');
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('companyclient.create')->with('Success', 'Company Client Profile Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', $th->getMessage());
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

    public function basicinfostore(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $iCounter = 0;
                foreach ($request->CompanyName as $CompanyName) {
                    if ($request->iCompanyClientId == 0) {
                        $Data = array(
                            "CompanyName" => $CompanyName,
                            "email" => $request->email[$iCounter],
                            "owner" => $request->owner[$iCounter],
                            "owneremail" => $request->owneremail[$iCounter],
                            "ownerphone" => $request->ownerphone[$iCounter],
                            "branchOffice" => $request->branchOffice[$iCounter],
                            "address" => $request->address[$iCounter],
                            "iCompanyId" => $request->company_id,
                            "iStateId" => $request->iStateId[$iCounter] ? $request->iStateId[$iCounter] : 0,
                            "iCityId" => $request->iCityId[$iCounter] ? $request->iCityId[$iCounter] : 0,
                            'iCompanyClientProfileId' => $request->iCompanyClientProfileId[$iCounter] ? $request->iCompanyClientProfileId[$iCounter] : 0,
                            "iCompanyId" => $request->company_id,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            'iEntryBy' => $session,
                            "strIP" => $request->ip()
                        );
                        $clientId = DB::table('companyclient')->insertGetId($Data);
                        Session::flash('Success', 'Customer Company Created Successfully.');
                    }else{
                        $Data = array(
                            "CompanyName" => $CompanyName,
                            "email" => $request->email[$iCounter],
                            "owner" => $request->owner[$iCounter],
                            "owneremail" => $request->owneremail[$iCounter],
                            "ownerphone" => $request->ownerphone[$iCounter],
                            "branchOffice" => $request->branchOffice[$iCounter],
                            "address" => $request->address[$iCounter],
                            "iCompanyId" => $request->company_id,
                            "iStateId" => $request->iStateId[$iCounter] ? $request->iStateId[$iCounter] : 0,
                            "iCityId" => $request->iCityId[$iCounter] ? $request->iCityId[$iCounter] : 0,
                            'iCompanyClientProfileId' => $request->iCompanyClientProfileId[$iCounter] ? $request->iCompanyClientProfileId[$iCounter] : 0,
                            "iCompanyId" => $request->company_id,
                            'iEntryBy' => $session,
                            "strIP" => $request->ip()
                        );

                        DB::table('companyclient')->where("iCompanyClientId", "=",$request->iCompanyClientId)->update($Data);
                        $clientId=$request->iCompanyClientId;
                        Session::flash('Success', 'Customer Company Updated Successfully.');
                    }
                    $iCounter++;
                }
                DB::commit();

                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Customer company",
                    'tableAutoId'    => $clientId,
                    'tableMainField'  => "Customer company Insert",
                    'action'     => "Insert",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                $save = $request->save;

                if ($save == '1') {
                    echo $clientId;
                } else {
                    return redirect()->route('companyclient.index');
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo '0';
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function editview(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'icompanyId' => $CompanyMaster->iCompanyId, "type" => 1])->get();
            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            $CompanyClients = CompanyClient::where(['companyclient.isDelete' => 0, 'iCompanyClientId' => $id])->first();
            return view('wladmin.companyclient.edit', compact('CompanyMaster', 'CompanyClientProfile', 'statemasters', 'citymasters', 'CompanyClients'));
        } else {
            return redirect()->route('home');
        }
    }
    public function infoview(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            //,'userdefined.type'=>'1'
            $CompanyClients = CompanyClient::where(['companyclient.isDelete' => 0, 'companyclient.iCompanyClientId' => $id])
                ->join('icompanyclientprofile', 'icompanyclientprofile.iCompanyClientProfileId', '=', 'companyclient.iCompanyClientProfileId', 'left outer')
                ->join('statemaster', 'statemaster.iStateId', '=', 'companyclient.iStateId', ' left outer')
                ->join('citymaster', 'citymaster.iCityId', '=', 'companyclient.iCityId', ' left outer')
                ->join('userdefined', 'userdefined.iCompanyClientId', '=', 'companyclient.iCompanyClientId', ' left outer')
                ->first();

            $infoTables = infoTable::where(["tableName" => "Customer company", "tableAutoId" => $id])->orderBy('id', 'Desc')->limit(10)->get();
            $salesperson = DB::table('salesperson')->where(['personType' => 1, "iCompanyClientId" => $id])->orderBy('iSalesId', 'DESC')->get();
            $technicalperson = DB::table('technicalperson')->where(['personType' => 1, "iCompanyClientId" => $id])->orderBy('iTechnicalId', 'DESC')->get();
            $CompanyClients['salesperson'] = $salesperson;
            $CompanyClients['technicalperson'] = $technicalperson;
            return view('wladmin.companyclient.info', compact('CompanyClients', 'infoTables'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $Data = array(
                    'CompanyName' => $request->CompanyName,
                    'email' => $request->email,
                    'owner' => $request->owner,
                    'owneremail' => $request->owneremail,
                    'ownerphone' => $request->ownerphone,
                    'address' => $request->address,
                    'iStateId' => $request->iStateId ? $request->iStateId : 0,
                    'iCityId' => $request->iCityId ? $request->iCityId : 0,
                    'branchOffice' => $request->branchOffice,
                    'iCompanyClientProfileId' => $request->iCompanyClientProfileId,
                    'strEntryDate' => date('Y-m-d H:i:s'),
                    'strIP' => $request->ip(),
                    'iEntryBy' => $session
                );
                DB::table('companyclient')->where("iCompanyClientId", "=", $request->iCompanyClientId)->update($Data);

                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Customer company",
                    'tableAutoId'    => $request->iCompanyClientId,
                    'tableMainField'  => "Customer company Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                $save = $request->save;
                Session::flash('Success', 'Customer Company Updated Successfully.');
                $id = $request->iCompanyClientId;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('companyclient.index');
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->iCompanyClientId;
                } else {
                    return redirect()->route('companyclient.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if(Auth::User()->role_id == 2){
            DB::table('companyclient')->where(['isDelete' => 0, 'iCompanyClientId' => $Id])->delete();
            return redirect()->route('companyclient.index')->with('Success', 'Customer Company Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }

    public function updateStatus(Request $request)
    {
        if ($request->status == 1) {
            CompanyClient::where(['iCompanyClientId' => $request->iCompanyClientId])->update(['iStatus' => 0]);
        } else {
            CompanyClient::where(['iCompanyClientId' => $request->iCompanyClientId])->update(['iStatus' => 1]);
        }
        echo 1;
    }

    public function salescreate(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            return view('wladmin.companyclient.addsales', compact('CompanyMaster', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function salesstore(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $iCounter = 0;
                foreach ($request->name as $salesName) {
                    $Data = array(
                        "salesPersonName" => $salesName,
                        "salesPersonNumber" => $request->phone[$iCounter],
                        "salesPersonEmail" => $request->email[$iCounter],
                        "personType" => '1',
                        "iCompanyId" => $request->company_id,
                        "iCompanyClientId" => $request->companyclient_id,
                        "entryDatetime" => date('Y-m-d H:i:s'),
                        'entryBy' => $session,
                        "strIP" => $request->ip()
                    );

                    $insertedId = DB::table('salesperson')->insertGetId($Data);

                    $iCounter++;
                }

                DB::commit();
                $save = $request->save;

                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Customer company",
                    'tableAutoId'    => $request->companyclient_id,
                    'tableMainField'  => "Customer company Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Customer Company Sales Person Created Successfully.');
                $id = $request->companyclient_id;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('companyclient.salescreate', compact('id'));
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->companyclient_id;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function saleseditview(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $CompanyClientProfile = CompanyClient::where(['companyclient.isDelete' => 0, 'iCompanyClientId' => $id])->first();
            $salesPersonList = DB::table('salesperson')->where(["iCompanyClientId" => $CompanyClientProfile->iCompanyClientId, "personType" => 1])->get();
            return view('wladmin.companyclient.salesedit', compact('CompanyClientProfile', 'salesPersonList'));
        } else {
            return redirect()->route('home');
        }
    }

    public function salesupdate(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $iCounter = 0;
                if (is_array($request->salesId)) {
                    foreach ($request->salesId as $isalesId) {
                        if ($isalesId == 0) {
                            $Data = array(
                                "salesPersonName" => $request->name[$iCounter],
                                "salesPersonNumber" => $request->phone[$iCounter],
                                "salesPersonEmail" => $request->email[$iCounter],
                                "personType" => '1',
                                "iCompanyId" => $request->company_id,
                                "iCompanyClientId" => $request->iCompanyClientId,
                                "entryDatetime" => date('Y-m-d H:i:s'),
                                'entryBy' => $session,
                                "strIP" => $request->ip()
                            );
                            $insertedId = DB::table('salesperson')->insertGetId($Data);
                        } else {
                            $Data = array(
                                "salesPersonName" => $request->name[$iCounter],
                                "salesPersonNumber" => $request->phone[$iCounter],
                                "salesPersonEmail" => $request->email[$iCounter],
                                "personType" => '1',
                                "strIP" => $request->ip()
                            );
                            DB::table('salesperson')->where("iSalesId", "=", $isalesId)->update($Data);
                        }

                        $iCounter++;
                    }
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Customer company",
                        'tableAutoId'    => $request->iCompanyClientId,
                        'tableMainField'  => "Customer company Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    Session::flash('Success', 'Customer Company Sales Person Edited Successfully.');
                    $save = $request->save;
                    $id = $request->iCompanyClientId;
                    if ($save == '1') {
                        echo $id;
                    } else {
                        return redirect()->route('companyclient.index');
                    }
                } else {
                    $Data = array(
                        "salesPersonName" => $request->name,
                        "salesPersonNumber" => $request->phone,
                        "salesPersonEmail" => $request->email,
                        "personType" => '1',
                        "strIP" => $request->ip()
                    );
                    DB::table('salesperson')->where("iSalesId", "=", $request->salesId)->update($Data);
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Customer company",
                        'tableAutoId'    => $request->iCompanyClientId,
                        'tableMainField'  => "Customer company Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    Session::flash('Success', 'Customer Company Sales Person Edited Successfully.');
                    echo $id = $request->iCompanyClientId;
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->iCompanyClientId;
                } else {

                    return redirect()->route('companyclient.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function salesdelete($Id)
    {
        DB::table('salesperson')->where(['isDelete' => 0, 'iSalesId' => $Id])->delete();
        Session::flash('Success', 'Customer Company Sales Person Deleted Successfully.');
        echo 1;
    }

    public function technicalcreate(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            return view('wladmin.companyclient.addtechnical', compact('CompanyMaster', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function technicalstore(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $iCounter = 0;
                foreach ($request->name as $salesName) {
                    $Data = array(
                        "technicalPersonName" => $salesName,
                        "technicalPersonNumber" => $request->phone[$iCounter],
                        "technicalPersonEmail" => $request->email[$iCounter],
                        "personType" => '1',
                        "iCompanyId" => $request->company_id,
                        "iCompanyClientId" => $request->companyclient_id,
                        "entryDatetime" => date('Y-m-d H:i:s'),
                        'entryBy' => $session,
                        "strIP" => $request->ip()
                    );
                    DB::table('technicalperson')->insertGetId($Data);
                    $iCounter++;
                }
                DB::commit();
                $save = $request->save;
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Customer company",
                    'tableAutoId'    => $request->companyclient_id,
                    'tableMainField'  => "Customer company Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Customer Company Technical Person Created Successfully.');
                $id = $request->companyclient_id;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('companyclient.technicalcreate', compact('id'));
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo $request->company_id;;
                } else {
                    return redirect()->route('companyclient.technicalcreate', compact('id'));
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function technicaleditview(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyClientProfile = CompanyClient::where(['companyclient.isDelete' => 0, 'iCompanyClientId' => $id])->first();
            $salesPersonList = DB::table('technicalperson')->where(["iCompanyClientId" => $CompanyClientProfile->iCompanyClientId, "personType" => 1])->get();
            return view('wladmin.companyclient.technicaledit', compact('CompanyClientProfile', 'salesPersonList'));
        } else {
            return redirect()->route('home');
        }
    }

    public function technicalupdate(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                $iCounter = 0;
                if (is_array($request->iTechnicalId)) {
                    foreach ($request->iTechnicalId as $isalesId) {
                        if ($isalesId == 0) {
                            $Data = array(
                                "technicalPersonName" => $request->name[$iCounter],
                                "technicalPersonNumber" => $request->phone[$iCounter],
                                "technicalPersonEmail" => $request->email[$iCounter],
                                "personType" => '1',
                                "iCompanyId" => $request->company_id,
                                "iCompanyClientId" => $request->iCompanyClientId,
                                "entryDatetime" => date('Y-m-d H:i:s'),
                                'entryBy' => $session,
                                "strIP" => $request->ip()
                            );
                            DB::table('technicalperson')->insertGetId($Data);
                        } else {
                            $Data = array(
                                "technicalPersonName" => $request->name[$iCounter],
                                "technicalPersonNumber" => $request->phone[$iCounter],
                                "technicalPersonEmail" => $request->email[$iCounter],
                                "personType" => '1',
                                "strIP" => $request->ip()
                            );
                            DB::table('technicalperson')->where("iTechnicalId", "=", $isalesId)->update($Data);
                        }
                        $iCounter++;
                    }
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Customer company",
                        'tableAutoId'    => $request->iCompanyClientId,
                        'tableMainField'  => "Customer company Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    Session::flash('Success', 'Customer Company Technical Person Edited Successfully.');
                    $save = $request->save;
                    $id = $request->iCompanyClientId;
                    if ($save == '1') {
                        echo $id;
                    } else {
                        return redirect()->route('companyclient.index');
                    }
                } else {
                    $Data = array(
                        "technicalPersonName" => $request->name,
                        "technicalPersonNumber" => $request->phone,
                        "technicalPersonEmail" => $request->email,
                        "personType" => '1',
                        "strIP" => $request->ip()
                    );
                    DB::table('technicalperson')->where("iTechnicalId", "=", $request->salesId)->update($Data);
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Customer company",
                        'tableAutoId'    => $request->iCompanyClientId,
                        'tableMainField'  => "Customer company Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    Session::flash('Success', 'Customer Company Technical Person Edited Successfully.');
                    $save = $request->save;
                    echo $id = $request->iCompanyClientId;
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->iCompanyClientId;
                } else {

                    return redirect()->route('companyclient.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function technicaldelete($Id)
    {
        DB::table('technicalperson')->where(['iTechnicalId' => $Id])->delete();
        Session::flash('Success', 'Customer Company Technical Person Deleted Successfully.');
        echo 1;
    }

    public function userdefinedcreate(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $userdefined = DB::table('userdefined')->where(['iCompanyClientId' => $id,'type'=>1])->first();

            return view('wladmin.companyclient.adduserdefine', compact('CompanyMaster', 'id', 'userdefined'));
        } else {
            return redirect()->route('home');
        }
    }

    public function userdefinededitview(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $userdefined = DB::table('userdefined')->where(['iCompanyClientId' => $id,'type'=>1])->first();

            return view('wladmin.companyclient.userdefinededit', compact('CompanyMaster', 'id', 'userdefined'));
        } else {
            return redirect()->route('home');
        }
    }

    public function userdefinedstore(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                if ($request->iUserDefineId != 0) {
                    $Data = array(
                        "userDefine1" => $request->userdefine1,
                        "userDefine2" => $request->userdefine2,
                        "userDefine3" => $request->userdefine3,
                        "type" => '1',
                        "iCompanyId" => $request->company_id,
                        "iCompanyClientId" => $request->companyclient_id,
                        'entryBy' => $session,

                    );
                    DB::table('userdefined')->where("iUserDefineId", "=", $request->iUserDefineId)->update($Data);
                    Session::flash('Success', 'Customer Company user Defined Edited Successfully.');
                } else {
                    $Data = array(
                        "userDefine1" => $request->userdefine1,
                        "userDefine2" => $request->userdefine2,
                        "userDefine3" => $request->userdefine3,
                        "type" => '1',
                        "iCompanyId" => $request->company_id,
                        "iCompanyClientId" => $request->companyclient_id,
                        "entryDatetime" => date('Y-m-d H:i:s'),
                        'entryBy' => $session,
                        "strIP" => $request->ip()
                    );
                    DB::table('userdefined')->insertGetId($Data);
                    Session::flash('Success', 'Customer Company user Defined Created Successfully.');
                }

                DB::commit();
                $save = $request->save;
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Customer company",
                    'tableAutoId'    => $request->companyclient_id,
                    'tableMainField'  => "Customer company Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                $id = $request->companyclient_id;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('companyclient.index');
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

    public function uploadindex(){
        if(Auth::User()->role_id == 2){
            return view('wladmin.companyclient.upload');
        } else {
            return redirect()->route('home');
        }
    }

    public function uploadsubmit(Request $request){
        if(Auth::User()->role_id == 2){
            $this->validate($request, [
                'strDocument'  => 'required|mimes:xls,xlsx'
            ]);
            $path = $request->file('strDocument')->getRealPath();

            //try{
                $spreadsheet = IOFactory::load($path);
                $sheet        = $spreadsheet->getActiveSheet();
                $row_limit    = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range    = range( 1, $row_limit );
                $column_range = range( 'F', $column_limit );
                $startcount = 0;
                $data = array();

                $iCount = 0;
                $Error = array();
                $ErrorNullValue = array();
                $errorString = "";

                foreach($row_range as $row){
                    if($iCount == 0){
                        if(trim($sheet->getCell( 'A' . $row )->getValue()) == "Sr. No"){
                            $Error[0] = true;
                        } else {
                            $Error[0] = false;
                        }
                        if(trim($sheet->getCell( 'B' . $row )->getValue()) == "Company Name"){
                            $Error[1] = true;
                        } else {
                            $Error[1] = false;
                        }

                        if(trim($sheet->getCell( 'C' . $row )->getValue()) == "Company Email ID"){
                            $Error[2] = true;
                        } else {
                            $Error[2] = false;
                        }
                        if(trim($sheet->getCell( 'D' . $row )->getValue()) == "Owner"){
                            $Error[3] = true;
                        } else {
                            $Error[3] = false;
                        }
                        if(trim($sheet->getCell( 'E' . $row )->getValue()) == "Owner Email ID"){
                            $Error[4] = true;
                        } else {
                            $Error[4] = false;
                        }
                        if(trim($sheet->getCell( 'F' . $row )->getValue()) == "Owner Phone"){
                            $Error[5] = true;
                        }else {
                            $Error[5] = false;
                        }
                        if(trim($sheet->getCell( 'G' . $row )->getValue()) == "Address"){
                            $Error[6] = true;
                        }else {
                            $Error[6] = false;
                        }
                        if(trim($sheet->getCell( 'H' . $row )->getValue()) == "State"){
                            $Error[7] = true;
                        }else {
                            $Error[7] = false;
                        }
                        if(trim($sheet->getCell( 'I' . $row )->getValue()) == "City"){
                            $Error[8] = true;
                        }else {
                            $Error[8] = false;
                        }
                        if(trim($sheet->getCell( 'J' . $row )->getValue()) == "Branch Office"){
                            $Error[9] = true;
                        }else {
                            $Error[9] = false;
                        }
                        if(trim($sheet->getCell( 'K' . $row )->getValue()) == "Company Profile"){
                            $Error[10] = true;
                        }else {
                            $Error[10] = false;
                        }
                        if(trim($sheet->getCell( 'L' . $row )->getValue()) == "Sales Person One Name"){
                            $Error[11] = true;
                        }else {
                            $Error[11] = false;
                        }
                        if(trim($sheet->getCell( 'M' . $row )->getValue()) == "Sales Person One Phone"){
                            $Error[12] = true;
                        }else {
                            $Error[12] = false;
                        }
                        if(trim($sheet->getCell( 'N' . $row )->getValue()) == "Sales Person One Email ID"){
                            $Error[13] = true;
                        }else {
                            $Error[13] = false;
                        }
                        if(trim($sheet->getCell( 'O' . $row )->getValue()) == "Sales Person Two Name"){
                            $Error[14]= true;
                        }else {
                            $Error[14] = false;
                        }
                        if(trim($sheet->getCell( 'P' . $row )->getValue()) == "Sales Person Two Phone"){
                            $Error[15] = true;
                        }else {
                            $Error[15] = false;
                        }
                        if(trim($sheet->getCell( 'Q' . $row )->getValue()) == "Sales Person Two Email ID"){
                            $Error[16] = true;
                        }else {
                            $Error[16] = false;
                        }
                        if(trim($sheet->getCell( 'R' . $row )->getValue()) == "Sales Person Three Name"){
                            $Error[17] = true;
                        }else {
                            $Error[17] = false;
                        }
                        if(trim($sheet->getCell( 'S' . $row )->getValue()) == "Sales Person Three Phone"){
                            $Error[18] = true;
                        }else {
                            $Error[18] = false;
                        }
                        if(trim($sheet->getCell( 'T' . $row )->getValue()) == "Sales Person Three Email ID"){
                            $Error[19] = true;
                        }else {
                            $Error[19] = false;
                        }
                        if(trim($sheet->getCell( 'U' . $row )->getValue()) == "Technical Person One Name"){
                            $Error[20] = true;
                        }else {
                            $Error[20] = false;
                        }
                        if(trim($sheet->getCell( 'V' . $row )->getValue()) == "Technical Person One Phone"){
                            $Error[21] = true;
                        }else {
                            $Error[21] = false;
                        }
                        if(trim($sheet->getCell( 'W' . $row )->getValue()) == "Technical Person One Email ID"){
                            $Error[22] = true;
                        }else {
                            $Error[22] = false;
                        }
                        if(trim($sheet->getCell( 'X' . $row )->getValue()) == "Technical Person Two Name"){
                            $Error[23] = true;
                        }else {
                            $Error[23] = false;
                        }
                        if(trim($sheet->getCell( 'Y' . $row )->getValue()) == "Technical Person Two Phone"){
                            $Error[24] = true;
                        }else {
                            $Error[24] = false;
                        }
                        if(trim($sheet->getCell( 'Z' . $row )->getValue()) == "Technical Person Two Email ID"){
                            $Error[25] = true;
                        }else {
                            $Error[25] = false;
                        }
                        if(trim($sheet->getCell( 'AA' . $row )->getValue()) == "Technical Person Three Name"){
                            $Error[26] = true;
                        }else {
                            $Error[26] = false;
                        }
                        if(trim($sheet->getCell( 'AB' . $row )->getValue()) == "Technical Person Three Phone"){
                            $Error[27] = true;
                        }else {
                            $Error[27] = false;
                        }
                        if(trim($sheet->getCell( 'AC' . $row )->getValue()) == "Technical Person Three Email ID"){
                            $Error[28] = true;
                        }else {
                            $Error[28] = false;
                        }
                        if(trim($sheet->getCell( 'AD' . $row )->getValue()) == "User Defined 1"){
                            $Error[29] = true;
                        }else {
                            $Error[29] = false;
                        }

                        if(trim($sheet->getCell( 'AE' . $row )->getValue()) == "User Defined 2"){
                            $Error[30] = true;
                        }else {
                            $Error[30] = false;
                        }
                        if(trim($sheet->getCell( 'AF' . $row )->getValue()) == "User Defined 3"){
                            $Error[31] = true;
                        }else {
                            $Error[31] = false;
                        }
                        break;
                    }
                }

                if($Error[0] == false || $Error[1] == false || $Error[2] == false || $Error[3] == false || $Error[4] == false || $Error[5] == false|| $Error[6] == false|| $Error[7] == false|| $Error[8] == false|| $Error[9] == false||$Error[10] == false||$Error[11] == false
                    ||$Error[12] == false||$Error[13] == false||$Error[14] == false||$Error[15] == false||$Error[16] == false||$Error[17] == false||$Error[18] == false||
                    $Error[19] == false||$Error[20] == false||$Error[21] == false||$Error[22] == false||$Error[23] == false||$Error[24] == false||$Error[25] == false ||$Error[26] == false ||$Error[27] == false
                    ||$Error[28] == false ||$Error[29] == false ||$Error[30] == false ||$Error[31] == false){
                        Session::flash('ErrorMsg', "Header Mismatch");
                        return back()->with('errors',"Header Mismatch");
                }

                foreach ($row_range as $row) {
                    if($startcount > 0){

                        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                        $companyclient = DB::table('companyclient')
                            ->where(['isDelete' => 0, "iStatus" => 1])
                            ->where('email','like',trim($sheet->getCell( 'C' . $row )->getValue()))
                            ->first();

                        if(empty($companyclient)){
                            if(trim($sheet->getCell( 'B' . $row )->getValue()) != "" && trim($sheet->getCell( 'C' . $row )->getValue()) != ""){
                                $statemasters = DB::table('statemaster')
                                    ->where(['isDelete' => 0, "iStatus" => 1])
                                    ->where('strStateName','like',trim($sheet->getCell( 'H' . $row )->getValue()))
                                    ->orderBy('strStateName', 'ASC')
                                    ->first();
                                if (empty($statemasters)) {
                                    $errorString .= "Row " . $startcount . " & State Name =" . trim($sheet->getCell( 'H' . $row )->getValue()) . " not match. <br/>";
                                }
                                $citymasters = DB::table('citymaster')
                                    ->where(['isDelete' => 0, "iStatus" => 1])
                                    ->where('strCityName','like',trim($sheet->getCell( 'I' . $row )->getValue()))
                                    ->orderBy('strCityName', 'ASC')
                                    ->first();
                                if (empty($citymasters)) {
                                    $errorString .= "Row " . $startcount . " & City Name =" . trim($sheet->getCell( 'I' . $row )->getValue()) . " not match. <br/>";
                                }
                                $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                                    ->orderBy('strOEMCompanyName', 'ASC')
                                    ->first();
                                $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $CompanyMaster->iCompanyId])
                                    ->where('strCompanyClientProfile','like',trim($sheet->getCell( 'K' . $row )->getValue()))
                                    ->first();
                                if (empty($CompanyClientProfile)) {
                                    $errorString .= "Row " . $startcount . " & Company Client Profile =" . trim($sheet->getCell( 'K' . $row )->getValue()) . " not match. <br/>";
                                }
                                if(!empty($statemasters) && !empty($citymasters) && !empty($CompanyClientProfile)){

                                    $Data = array(
                                        "CompanyName" => trim($sheet->getCell( 'B' . $row )->getValue()),
                                        "email" => trim($sheet->getCell( 'C' . $row )->getValue()),
                                        "owner" => trim($sheet->getCell( 'D' . $row )->getValue()),
                                        "owneremail" => trim($sheet->getCell( 'E' . $row )->getValue()),
                                        "ownerphone" => trim($sheet->getCell( 'F' . $row )->getValue()),
                                        "address" => trim($sheet->getCell( 'G' . $row )->getValue()),
                                        "branchOffice" => trim($sheet->getCell( 'J' . $row )->getValue()),
                                        "iCompanyId" => $request->iCompanyId,
                                        "iStateId" => $statemasters->iStateId ?? 0,
                                        "iCityId" => $citymasters->iCityId ?? 0,
                                        'iCompanyClientProfileId' => $CompanyClientProfile->iCompanyClientProfileId ?? 0,
                                        "strEntryDate" => date('Y-m-d H:i:s'),
                                        'iEntryBy' => $session,
                                        "strIP" => $request->ip()
                                    );
                                    $clientId = DB::table('companyclient')->insertGetId($Data);

                                    $userdata = User::whereId($session)->first();
                                    // $infoClinetArr = array(
                                    //     'tableName'    => "Customer company",
                                    //     'tableAutoId'    => $clientId,
                                    //     'tableMainField'  => "Customer company Insert",
                                    //     'action'     => "Insert",
                                    //     'strEntryDate' => date("Y-m-d H:i:s"),
                                    //     'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                                    // );
                                    // $Info = infoTable::create($infoClinetArr);

                                    if(trim($sheet->getCell( 'L' . $row )->getValue()) != "" || trim($sheet->getCell( 'M' . $row )->getValue()) != "" || trim($sheet->getCell( 'N' . $row )->getValue()) != ""){
                                        $dataSalesPersonOne = array(
                                            "salesPersonName" => trim($sheet->getCell( 'L' . $row )->getValue()),
                                            "salesPersonNumber" => trim($sheet->getCell( 'M' . $row )->getValue()),
                                            "salesPersonEmail" => trim($sheet->getCell( 'N' . $row )->getValue()),
                                            "personType" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        $insertedId = DB::table('salesperson')->insertGetId($dataSalesPersonOne);
                                    }
                                    if(trim($sheet->getCell( 'O' . $row )->getValue()) != "" || trim($sheet->getCell( 'P' . $row )->getValue()) != "" || trim($sheet->getCell( 'Q' . $row )->getValue()) != ""){
                                        $dataSalesPersonTwo = array(
                                            "salesPersonName" => trim($sheet->getCell( 'O' . $row )->getValue()),
                                            "salesPersonNumber" => trim($sheet->getCell( 'P' . $row )->getValue()),
                                            "salesPersonEmail" => trim($sheet->getCell( 'Q' . $row )->getValue()),
                                            "personType" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        $insertedId = DB::table('salesperson')->insertGetId($dataSalesPersonTwo);
                                    }
                                    if(trim($sheet->getCell( 'R' . $row )->getValue()) != "" || trim($sheet->getCell( 'S' . $row )->getValue()) != "" || trim($sheet->getCell( 'T' . $row )->getValue()) != ""){
                                        $dataSalesPersonThree = array(
                                            "salesPersonName" => trim($sheet->getCell( 'R' . $row )->getValue()),
                                            "salesPersonNumber" => trim($sheet->getCell( 'S' . $row )->getValue()),
                                            "salesPersonEmail" => trim($sheet->getCell( 'T' . $row )->getValue()),
                                            "personType" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        $insertedId = DB::table('salesperson')->insertGetId($dataSalesPersonThree);
                                    }
                                    if(trim($sheet->getCell( 'U' . $row )->getValue()) != "" || trim($sheet->getCell( 'V' . $row )->getValue()) != "" || trim($sheet->getCell( 'W' . $row )->getValue()) != ""){
                                        $technicalPersonData = array(
                                            "technicalPersonName" => trim($sheet->getCell( 'U' . $row )->getValue()),
                                            "technicalPersonNumber" => trim($sheet->getCell( 'V' . $row )->getValue()),
                                            "technicalPersonEmail" => trim($sheet->getCell( 'W' . $row )->getValue()),
                                            "personType" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        DB::table('technicalperson')->insertGetId($technicalPersonData);
                                    }
                                    if(trim($sheet->getCell( 'X' . $row )->getValue()) != "" || trim($sheet->getCell( 'Y' . $row )->getValue()) != "" || trim($sheet->getCell( 'Z' . $row )->getValue()) != ""){
                                        $technicalPersonTwoData = array(
                                            "technicalPersonName" => trim($sheet->getCell( 'X' . $row )->getValue()),
                                            "technicalPersonNumber" => trim($sheet->getCell( 'Y' . $row )->getValue()),
                                            "technicalPersonEmail" => trim($sheet->getCell( 'Z' . $row )->getValue()),
                                            "personType" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        DB::table('technicalperson')->insertGetId($technicalPersonTwoData);
                                    }
                                    if(trim($sheet->getCell( 'AA' . $row )->getValue()) != "" || trim($sheet->getCell( 'AB' . $row )->getValue()) != "" || trim($sheet->getCell( 'AC' . $row )->getValue()) != ""){
                                        $technicalPersonThreeData = array(
                                            "technicalPersonName" => trim($sheet->getCell( 'AA' . $row )->getValue()),
                                            "technicalPersonNumber" => trim($sheet->getCell( 'AB' . $row )->getValue()),
                                            "technicalPersonEmail" => trim($sheet->getCell( 'AC' . $row )->getValue()),
                                            "personType" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        DB::table('technicalperson')->insertGetId($technicalPersonThreeData);
                                    }
                                    if(trim($sheet->getCell( 'AD' . $row )->getValue()) != "" || trim($sheet->getCell( 'AE' . $row )->getValue()) != "" || trim($sheet->getCell( 'AF' . $row )->getValue()) != ""){
                                        $userdefineData = array(
                                            "userDefine1" => trim($sheet->getCell( 'AD' . $row )->getValue()),
                                            "userDefine2" => trim($sheet->getCell( 'AE' . $row )->getValue()),
                                            "userDefine3" => trim($sheet->getCell( 'AF' . $row )->getValue()),
                                            "type" => '1',
                                            "iCompanyId" => $request->iCompanyId,
                                            "iCompanyClientId" => $clientId,
                                            "entryDatetime" => date('Y-m-d H:i:s'),
                                            'entryBy' => $session,
                                            "strIP" => $request->ip()
                                        );
                                        DB::table('userdefined')->insertGetId($userdefineData);
                                    }
                                    $infoTechnicalPersonArr = array(
                                        'tableName'    => "Customer company",
                                        'tableAutoId'    => $clientId,
                                        'tableMainField'  => "Customer company Upload By Excel",
                                        'action'     => "Update",
                                        'strEntryDate' => date("Y-m-d H:i:s"),
                                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                                    );
                                    $Info = infoTable::create($infoTechnicalPersonArr);

                                }
                            } else {
                                $errorString .= "Row " . $startcount . " & Company Name or Email is empty. <br/>";
                            }
                        } else {
                            $errorString .= "Row " . $startcount . " & Email =" . trim($sheet->getCell( 'C' . $row )->getValue()) . " is already exist. <br/>";
                        }

                    }
                    $startcount++;
                }

            // } catch (Exception $e) {
            //     $error_code = $e->errorInfo[1];
            //     return back()->with('errors',['There was a problem uploading the data!']);
            // }
            //dd($errorString);
            if($errorString != ""){
                Session::flash('ErrorMsg', $errorString);
            } else {
                Session::flash('Success', 'Customer Company user Defined Created Successfully.');
            }
            return back();

        } else {
            return redirect()->route('home');
        }
    }

    public function genrateCompanyClientExcel(Request $request){

        if (Auth::User()->role_id == 2) {
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $user = User::where(["id" => $session])->first();
            $CompanyClients = CompanyClient::select('companyclient.*', 'strStateName', 'strCityName', 'strCompanyClientProfile')
                ->join('icompanyclientprofile', 'icompanyclientprofile.iCompanyClientProfileId', '=', 'companyclient.iCompanyClientProfileId', 'left outer')
                ->join('statemaster', 'statemaster.iStateId', '=', 'companyclient.iStateId', ' left outer')
                ->join('citymaster', 'citymaster.iCityId', '=', 'companyclient.iCityId', ' left outer')
                ->when($request->search_name, fn ($query, $search_name) => $query->where('companyclient.CompanyName', $search_name))
                ->when($request->search_email, fn ($query, $search_email) => $query->where('companyclient.email', $search_email))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('companyclient.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('companyclient.iCityId', $search_city))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('companyclient.strEntryDate', [$formDate, $toDate]))
                ->orderBy('iCompanyClientId', 'DESC')->where(['companyclient.isDelete' => 0, 'companyclient.iCompanyId' => $CompanyMaster->iCompanyId])->get();
            return view('wladmin.companyclient.download', compact('CompanyClients'));
        } else {
            return redirect()->route('home');
        }
    }
}
