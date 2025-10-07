<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\infoTable;
use App\Models\CompanyClientProfile;
use App\Models\Loginlog;
use App\Models\User;
use Auth;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $search_name = $request->search_name;
            $search_contact = $request->search_contact;
            $search_email = $request->search_email;
            $search_state = $request->search_state;
            $search_city = $request->search_city;
            $daterange = $request->daterange;
            $search_daterange= $request->daterange;
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
            $distributors = Distributor::select('companydistributor.*', 'statemaster.strStateName', 'citymaster.strCityName','users.first_name','users.last_name')->orderBy('iDistributorId', 'DESC')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'companydistributor.iStateId')
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'companydistributor.iCityId')
                ->join('users', 'users.id', '=', 'companydistributor.iEntryBy','left outer')
                ->when($request->search_name, fn ($query, $search_name) => $query->where('companydistributor.Name', $search_name))
                ->when($request->search_contact, fn ($query, $search_contact) => $query->where('companydistributor.distributorPhone', $search_contact))
                ->when($request->search_email, fn ($query, $search_email) => $query->where('companydistributor.EmailId', $search_email))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('companydistributor.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('companydistributor.iCityId', $search_city))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('companydistributor.strEntryDate', [$formDate, $toDate]))
                ->where(['companydistributor.isDelete' => 0, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            return view('wladmin.distributor.index', compact('distributors', 'statemasters', 'citymasters', 'user', 'search_name', 'search_contact', 'search_email', 'search_state', 'search_city', 'search_daterange'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            if ($id != 0)
                $Distributor = Distributor::orderBy('iDistributorId', 'DESC')->where(['iDistributorId' => $id, 'isDelete' => 0, 'iStatus' => 1])->first();
            else
                $Distributor = null;

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $CompanyMaster->iCompanyId, "type" => 2])->get();
            return view('wladmin.distributor.add', compact('Distributor', 'statemasters', 'citymasters', 'CompanyMaster', 'id', 'CompanyClientProfile'));
        } else {
            return redirect()->route('home');
        }
    }

    public function create(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $icounter = 0;
                foreach ($request->Name as $Name) {
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $Data = array(
                        'iCompanyId' => $request->iCompanyId,
                        'iProfileId' => $request->iCompanyClientProfileId[$icounter] ?? 0,
                        'Name' => $Name,
                        'EmailId' => $request->EmailId[$icounter],
                        'distributorPhone' => $request->distributorPhone[$icounter],
                        'Address' => $request->Address[$icounter],
                        'iStateId' => $request->iStateId[$icounter] ? $request->iStateId[$icounter] : 0,
                        'iCityId' => $request->iCityId[$icounter] ? $request->iCityId[$icounter] : 0,
                        'branchOffice' => $request->branchOffice[$icounter],
                        'strEntryDate' => date('Y-m-d H:i:s'),
                        'strIP' => $request->ip(),
                        'iEntryBy' => $session
                    );
                    $companyuser = DB::table('companydistributor')->insertGetId($Data);
                    $userdata = \App\Models\User::whereId($session)->first();
                    $icounter++;
                }
                $save = $request->save;
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Distributor",
                    'tableAutoId'    =>  $companyuser,
                    'tableMainField'  => "Distributor Insert",
                    'action'     => "Insert",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'distributor Created Successfully.');
                if ($save == '1') {
                    echo $companyuser;
                } else {
                    return redirect()->route('distributor.index');
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
        if (Auth::User()->role_id == 2) {
            $Distributor = Distributor::where(['isDelete' => 0, 'iDistributorId' => $id])->first();

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $CompanyClientProfile = CompanyClientProfile::where(['isDelete' => 0, 'iStatus' => 1, 'icompanyId' => $CompanyMaster->iCompanyId, "type" => 2])->get();
            return view('wladmin.distributor.edit', compact('Distributor', 'statemasters', 'citymasters', 'CompanyClientProfile'));
        } else {
            return redirect()->route('home');
        }
    }
    public function infoview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            $CompanyClients = Distributor::where(['companydistributor.isDelete' => 0, 'companydistributor.iDistributorId' => $id,'userdefined.type'=>'2'])
                 ->join('icompanyclientprofile', 'icompanyclientprofile.iCompanyClientProfileId', '=', 'companydistributor.iProfileId', 'left outer')
                ->join('statemaster', 'statemaster.iStateId', '=', 'companydistributor.iStateId', ' left outer')
                ->join('citymaster', 'citymaster.iCityId', '=', 'companydistributor.iCityId', ' left outer')
                ->join('userdefined', 'userdefined.iCompanyClientId', '=', 'companydistributor.iDistributorId', ' left outer')
                ->first();
            $infoTables = infoTable::where(["tableName" => "Distributor", "tableAutoId" => $id])->orderBy('id', 'Desc')->limit(10)->get();
            $salesperson = DB::table('salesperson')->where(['personType' => 2, "iCompanyClientId" => $id])->orderBy('iSalesId', 'DESC')->get();
            $technicalperson = DB::table('technicalperson')->where(['personType' => 2, "iCompanyClientId" => $id])->orderBy('iTechnicalId', 'DESC')->get();
            $CompanyClients['salesperson'] = $salesperson;
            $CompanyClients['technicalperson'] = $technicalperson;


            return view('wladmin.distributor.info', compact('CompanyClients', 'infoTables'));
        } else {
            return redirect()->route('home');
        }
    }
    public function update(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $Data = array(
                    'Name' => $request->Name,
                    'EmailId' => $request->EmailId,
                    'iProfileId' => $request->iCompanyClientProfileId ?? 0,
                    'distributorPhone' => $request->distributorPhone,
                    'Address' => $request->Address,
                    'iStateId' => $request->iStateId ? $request->iStateId : 0,
                    'iCityId' => $request->iCityId ? $request->iCityId : 0,
                    'branchOffice' => $request->branchOffice,
                    'strEntryDate' => date('Y-m-d H:i:s'),
                    'strIP' => $request->ip(),
                    'iEntryBy' => $session
                );
                DB::table('companydistributor')->where("iDistributorId", "=", $request->iDistributorId)->update($Data);
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Distributor",
                    'tableAutoId'    =>  $request->iDistributorId,
                    'tableMainField'  => "Distributor Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                $save = $request->save;
                Session::flash('Success', 'distributor Updated Successfully.');
                $id = $request->iDistributorId;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('distributor.index');
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->iDistributorId;
                } else {
                    return redirect()->route('distributor.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if (Auth::User()->role_id == 2) {
            DB::table('companydistributor')->where(['isDelete' => 0, 'iDistributorId' => $Id])->delete();
            return redirect()->route('distributor.index')->with('Success', 'Company Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }

    public function updateStatus(Request $request)
    {
        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        if ($request->status == 1) {
            Distributor::where(['iDistributorId' => $request->iDistributorId])->update(['iStatus' => 0]);
        } else {
            Distributor::where(['iDistributorId' => $request->iDistributorId])->update(['iStatus' => 1]);
        }
        $userdata = User::whereId($session)->first();
        $infoArr = array(
            'tableName'    => "Distributor",
            'tableAutoId'    =>  $request->iDistributorId,
            'tableMainField'  => "Distributor Update",
            'action'     => "Update",
            'strEntryDate' => date("Y-m-d H:i:s"),
            'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
        );
        $Info = infoTable::create($infoArr);
        echo 1;
    }

    public function salescreate(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            return view('wladmin.distributor.addsales', compact('CompanyMaster', 'id'));
        } else {
            return redirect()->route('home');
        }
    }
    public function salesstore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $iCounter = 0;
                foreach ($request->name as $salesName) {
                    $Data = array(
                        "salesPersonName" => $salesName,
                        "salesPersonNumber" => $request->phone[$iCounter],
                        "salesPersonEmail" => $request->email[$iCounter],
                        "personType" => '2',
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
                    'tableName'    => "Distributor",
                    'tableAutoId'    =>  $request->companyclient_id,
                    'tableMainField'  => "Distributor Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Distributor Sales Person Created Successfully.');
                $id = $request->companyclient_id;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('distributor.index');
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->companyclient_id;
                } else {
                    return redirect()->route('distributor.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function saleseditview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $Distributor = Distributor::where(['isDelete' => 0, 'iDistributorId' => $id])->first();
            $salesPersonList = DB::table('salesperson')->where(["iCompanyClientId" => $id, "personType" => 2])->get();

            return view('wladmin.distributor.salesedit', compact('Distributor', 'salesPersonList'));
        } else {
            return redirect()->route('home');
        }
    }

    public function salesupdate(Request $request)
    {
        if (Auth::User()->role_id == 2) {
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
                                "personType" => '2',
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
                                "personType" => '2',
                                "strIP" => $request->ip()
                            );
                            DB::table('salesperson')->where("iSalesId", "=", $isalesId)->update($Data);
                        }
                        $iCounter++;
                    }
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Distributor",
                        'tableAutoId'    =>  $request->iCompanyClientId,
                        'tableMainField'  => "Distributor Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    Session::flash('Success', 'Distributor Sales Person Edited Successfully.');
                    $save = $request->save;
                    $id = $request->iCompanyClientId;
                    if ($save == '1') {
                        echo $id;
                    } else {
                        return redirect()->route('distributor.index');
                    }
                } else {
                    $Data = array(
                        "salesPersonName" => $request->name,
                        "salesPersonNumber" => $request->phone,
                        "salesPersonEmail" => $request->email,
                        "personType" => '2',
                        "strIP" => $request->ip()
                    );
                    DB::table('salesperson')->where("iSalesId", "=", $request->salesId)->update($Data);
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Distributor",
                        'tableAutoId'    =>  $request->iCompanyClientId,
                        'tableMainField'  => "Distributor Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    Session::flash('Success', 'Distributor Sales Person Edited Successfully.');
                    echo $id = $request->iCompanyClientId;
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->iCompanyClientId;
                } else {
                    return redirect()->route('distributor.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function salesdelete($Id)
    {
        if (Auth::User()->role_id == 2) {
            DB::table('salesperson')->where(['isDelete' => 0, 'iSalesId' => $Id])->delete();
            Session::flash('Success', 'distributor Sales Person Deleted Successfully.');
            echo 1;
        } else {
            return redirect()->route('home');
        }
    }

    public function technicalcreate(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            return view('wladmin.distributor.addtechnical', compact('CompanyMaster', 'id'));
        } else {
            return redirect()->route('home');
        }
    }
    public function technicalstore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $iCounter = 0;
                foreach ($request->name as $salesName) {
                    $Data = array(
                        "technicalPersonName" => $salesName,
                        "technicalPersonNumber" => $request->phone[$iCounter],
                        "technicalPersonEmail" => $request->email[$iCounter],
                        "personType" => '2',
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
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Distributor",
                    'tableAutoId'    =>  $request->companyclient_id,
                    'tableMainField'  => "Distributor Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                $save = $request->save;
                Session::flash('Success', 'Distributor Technical Person Created Successfully.');
                $id = $request->companyclient_id;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('distributor.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo $request->companyclient_id;
                } else {
                    return redirect()->route('distributor.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function technicaleditview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $Distributor = Distributor::where(['isDelete' => 0, 'iDistributorId' => $id])->first();
            $salesPersonList = DB::table('technicalperson')->where(["iCompanyClientId" => $Distributor->iDistributorId, "personType" => 2])->get();
            return view('wladmin.distributor.technicaledit', compact('Distributor', 'salesPersonList'));
        } else {
            return redirect()->route('home');
        }
    }

    public function technicalupdate(Request $request)
    {
        if (Auth::User()->role_id == 2) {
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
                                "personType" => '2',
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
                                "personType" => '2',
                                "strIP" => $request->ip()
                            );
                            DB::table('technicalperson')->where("iTechnicalId", "=", $isalesId)->update($Data);
                        }
                        $iCounter++;
                    }
                    Session::flash('Success', 'Distributor Technical Person Edited Successfully.');
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Distributor",
                        'tableAutoId'    =>  $request->iCompanyClientId,
                        'tableMainField'  => "Distributor Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    $save = $request->save;
                    $id = $request->iCompanyClientId;
                    if ($save == '1') {
                        echo $id;
                    } else {
                        return redirect()->route('distributor.index');
                    }
                } else {
                    $Data = array(
                        "technicalPersonName" => $request->name,
                        "technicalPersonNumber" => $request->phone,
                        "technicalPersonEmail" => $request->email,
                        "personType" => '2',
                        "strIP" => $request->ip()
                    );
                    DB::table('technicalperson')->where("iTechnicalId", "=", $request->salesId)->update($Data);
                    Session::flash('Success', 'Distributor Technical Person Edited Successfully.');
                    $userdata = User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "Distributor",
                        'tableAutoId'    =>  $request->iCompanyClientId,
                        'tableMainField'  => "Distributor Update",
                        'action'     => "Update",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                    echo $id = $request->iCompanyClientId;
                }
            } catch (\Throwable $th) {
                Session::flash('Error', $th->getMessage());
                DB::rollBack();
                $save = $request->save;
                if ($save == '1') {
                    echo $request->iCompanyClientId;
                } else {
                    return redirect()->route('distributor.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function technicaldelete($Id)
    {
        DB::table('technicalperson')->where(['iTechnicalId' => $Id])->delete();
        Session::flash('Success', 'Distributor Technical Person Deleted Successfully.');
        echo 1;
    }
    public function userdefinedcreate(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $userdefined = DB::table('userdefined')->where(['iCompanyClientId' => $id,'type'=>2])->first();

            return view('wladmin.distributor.adduserdefine', compact('CompanyMaster', 'id', 'userdefined'));
        } else {
            return redirect()->route('home');
        }
    }
    public function userdefinedstore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

                if ($request->iUserDefineId != 0) {
                    $Data = array(
                        "userDefine1" => $request->userdefine1,
                        "userDefine2" => $request->userdefine2,
                        "userDefine3" => $request->userdefine3,
                        "type" => '2',
                        "iCompanyId" => $request->company_id,
                        "iCompanyClientId" => $request->companyclient_id,
                        'entryBy' => $session,

                    );
                    DB::table('userdefined')->where("iUserDefineId", "=", $request->iUserDefineId)->update($Data);
                    Session::flash('Success', 'Distributor User Define Edited Successfully.');
                } else {
                    $Data = array(
                        "userDefine1" => $request->userdefine1,
                        "userDefine2" => $request->userdefine2,
                        "userDefine3" => $request->userdefine3,
                        "type" => '2',
                        "iCompanyId" => $request->company_id,
                        "iCompanyClientId" => $request->companyclient_id,
                        "entryDatetime" => date('Y-m-d H:i:s'),
                        'entryBy' => $session,
                        "strIP" => $request->ip()
                    );
                    DB::table('userdefined')->insertGetId($Data);
                    Session::flash('Success', 'Distributor user Defined Created Successfully.');
                }

                DB::commit();
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Distributor",
                    'tableAutoId'    =>  $request->companyclient_id,
                    'tableMainField'  => "Distributor Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                $save = $request->save;
                $id = $request->companyclient_id;
                if ($save == '1') {
                    echo $id;
                } else {
                    return redirect()->route('distributor.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();

                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo $request->companyclient_id;;
                } else {
                    return redirect()->route('distributor.index');
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function userdefinededitview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $userdefined = DB::table('userdefined')->where(['iCompanyClientId' => $id,'type'=>2])->first();

            return view('wladmin.distributor.userdefinededit', compact('CompanyMaster', 'id', 'userdefined'));
        } else {
            return redirect()->route('home');
        }
    }

    public function genrateDistributorExcel(Request $request){

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
            $distributors = Distributor::select('companydistributor.*', 'statemaster.strStateName', 'citymaster.strCityName')->orderBy('iDistributorId', 'DESC')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'companydistributor.iStateId')
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'companydistributor.iCityId')
                ->when($request->search_name, fn ($query, $search_name) => $query->where('companydistributor.Name', $search_name))
                ->when($request->search_contact, fn ($query, $search_contact) => $query->where('companydistributor.distributorPhone', $search_contact))
                ->when($request->search_email, fn ($query, $search_email) => $query->where('companydistributor.EmailId', $search_email))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('companydistributor.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('companydistributor.iCityId', $search_city))
                ->when($request->daterange, fn ($query, $daterange) => $query->whereBetween('companydistributor.strEntryDate', [$formDate, $toDate]))
                ->where(['companydistributor.isDelete' => 0, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            return view('wladmin.distributor.download', compact('distributors'));
        } else {
            return redirect()->route('home');
        }
    }
}
