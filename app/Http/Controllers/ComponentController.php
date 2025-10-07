<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Component;
use Illuminate\Support\Facades\DB;
use App\Models\infoTable;
use Illuminate\Support\Facades\Session;
use App\Models\SubComponent;
use App\Models\ResolutionCategory;
use App\Models\IssueType;
use App\Models\CallCompetency;
use App\Models\SupportType;
use App\Models\System;
use App\Models\User;
use App\Models\CompanyMaster;
use Auth;

class ComponentController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            $components = Component::select('companymaster.strOEMCompanyName', 'system.strSystem', 'component.iCompanyId', 'strComponent', 'IsSubComponent', 'iComponentId')
                ->join('companymaster', 'companymaster.iCompanyId', '=', 'component.iCompanyId')
                ->join('system', 'system.iSystemId', '=', 'component.strSystem')
                ->where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->when($request->search_system, fn($query, $search_system) => $query->where('component.strSystem', $search_system))
                ->when($request->search_component, fn($query, $search_component) => $query->where('iComponentId', $search_component))
                ->orderBy('component.iComponentId', 'DESC')
                ->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->when($request->search_system, fn($query, $search_system) => $query->where('component.strSystem', $search_system))
                ->orderBy('component.strComponent', 'ASC')
                ->get();
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $CompanyMaster->iCompanyId])->distinct()->get();
            $search_system = $request->search_system;
            $search_component = $request->search_component;
            return view('wladmin.component.index', compact('components', 'CompanyMaster', 'subcomponents', 'componentLists', 'systems', 'search_system', 'search_component'));
        } else {
            return redirect()->route('home');
        }
    }

    public function getcomponent(Request $request)
    {

        $componentLists = Component::where(['isDelete' => 0, 'iStatus' => 1, "strSystem" => $request->search_system])
            ->orderBy('component.strComponent', 'ASC')
            ->get();
        $html = "";
        $html .= '<option label="Please Select" value="">-- Select --</option>';
        foreach ($componentLists as $component) {
            $html .= '<option value="' . $component->iComponentId . '">' . $component->strComponent . '</option>';
        }
        echo $html;
    }
    public function createview()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            return view('wladmin.component.add', compact('CompanyMaster'));
        } else {
            return redirect()->route('home');
        }
    }

    public function companycomponentstore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $iCounter = 0;
                foreach ($request->strSystem as $strSystem) {
                    if ($strSystem != "") {
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
                        //Component::
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
                        'tableMainField'  => "Component Insert",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                $save = $request->save;
                Session::flash('Success', 'Company Component Created Successfully.');
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('component.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', 'Invlid Request');
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

    public function general()
    {
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
            return view('wladmin.component.general', compact('CompanyMaster', 'statemasters', 'citymasters'));
        } else {
            return redirect()->route('home');
        }
    }

    public function resolutioncategory()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $resolutionCategories = ResolutionCategory::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->get();

            return view('wladmin.component.resolutionCategory', compact('CompanyMaster', 'resolutionCategories'));
        } else {
            return redirect()->route('home');
        }
    }

    public function resolutioncategorycreatestore(Request $request)
    {
        
        if (Auth::User()->role_id == 2) {
            // try {
                //DB::table('resolutioncategory')->where("iCompanyId" ,"=",$request->company_id)->delete();
                $id = array();
                foreach ($request->iResolutionCategoryId as $iResolutionCategoryId) {
                    array_push($id, $iResolutionCategoryId);
                }
                // $resolutioncategory = DB::table('resolutioncategory')->whereNotIn("iResolutionCategoryId", $id)->get();
                // dd($resolutioncategory);
                DB::table('resolutioncategory')->whereNotIn("iResolutionCategoryId", $id)->where("iCompanyId", '=', $request->company_id)->update(["isDelete" => 1]);
                $iCounter = 0;
                $resolutionCategory = 0;
                foreach ($request->field_name as $strResolutionCategory) {
                    $resolution_category = DB::table('resolutioncategory')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                        ->where('strResolutionCategory', 'like', trim($strResolutionCategory))->count();
                    
                        if ($strResolutionCategory != "") {
                            if ($resolution_category ==  0) {
                                $Data = array(
                                    'strResolutionCategory' => $strResolutionCategory,
                                    "iCompanyId" => $request->company_id,
                                    "strEntryDate" => date('Y-m-d H:i:s'),
                                    "strIP" => $request->ip()
                                );
                            
                                if ($request->iResolutionCategoryId[$iCounter] != 0) {
                                    $resolutionCategory = $request->iResolutionCategoryId[$iCounter];
                                    if ($strResolutionCategory != "") {
                                        DB::table('resolutioncategory')->where("iResolutionCategoryId", "=", $request->iResolutionCategoryId[$iCounter])->update($Data);
                                    } else {
                                        DB::table('resolutioncategory')->where("iResolutionCategoryId", "=", $request->iResolutionCategoryId[$iCounter])->delete();
                                    }
                                } else {
                                    $resolutionCategory = DB::table('resolutioncategory')->insertGetId($Data);
                                }
                            }
                        }
                            
                        
                    

                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "resolutioncategory",
                        'tableAutoId'    => $resolutionCategory,
                        'tableMainField'  => "resolutionCategory",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                
                DB::commit();
                $save = $request->save;
                Session::flash('Success', 'Company Solution Type Created Successfully.');
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('component.index');
                }
            // } catch (\Throwable $th) {
            //     DB::rollBack();
            //     $save = $request->save;
            //     Session::flash('Error', 'Invlid Request');
            //     if ($save == '1') {
            //         echo $request->company_id;
            //     } else {
            //         return redirect()->back()->withInput()->with('Error', $th->getMessage());
            //     }
            // }
        } else {
            return redirect()->route('home');
        }
    }

    public function issuetypecreate()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $issuetypes = IssueType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            return view('wladmin.component.issueType', compact('CompanyMaster', 'issuetypes'));
        } else {
            return redirect()->route('home');
        }
    }

    public function issuetypestore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
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
                
                    if ($strIssueType != "") {
                        if($issue_type == 0){        
                            $Data = array(
                                'strIssueType' => $strIssueType,
                                "iCompanyId" => $request->company_id,
                                "strEntryDate" => date('Y-m-d H:i:s'),
                                "strIP" => $request->ip()
                            );
                            if ($request->iSSueTypeId[$iCounter] != 0) {
                                $issuetype = $request->iSSueTypeId[$iCounter];
                                if ($strIssueType != "") {
                                    DB::table('issuetype')->where("iSSueTypeId", "=", $request->iSSueTypeId[$iCounter])->update($Data);
                                } else {
                                    DB::table('issuetype')->where("iSSueTypeId", "=", $request->iSSueTypeId[$iCounter])->delete();
                                }
                            } else {
                                $issuetype = DB::table('issuetype')->insertGetId($Data);
                            }
                        }
                    }
    
                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "issuetype",
                        'tableAutoId'    => $issuetype,
                        'tableMainField'  => "IssueType",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                DB::commit();
                $save = $request->save;
                Session::flash('Success', 'Company Issue Type Created Successfully.');
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('component.index')->with('Success', 'Company Issue Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', 'Invlid Request');
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

    public function CallCompetency()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $callcompetencies = CallCompetency::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            return view('wladmin.component.CallCompetency', compact('CompanyMaster', 'callcompetencies'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callcompetencystore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
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
                    
                    if ($strCallCompetency != "") {
                        if($call_competency == 0){
                            $Data = array(
                                'strCallCompetency' => $strCallCompetency,
                                "iCompanyId" => $request->company_id,
                                "strEntryDate" => date('Y-m-d H:i:s'),
                                "strIP" => $request->ip()
                            );
                            
                    
                            if ($request->iCallCompetency[$iCounter] != 0) {
                                $callcompetency = $request->iCallCompetency[$iCounter];
                                if ($strCallCompetency != "") {
                                    DB::table('callcompetency')->where("iCallCompetency", "=", $request->iCallCompetency[$iCounter])->update($Data);
                                } else {
                                    DB::table('callcompetency')->where("iCallCompetency", "=", $request->iCallCompetency[$iCounter])->delete();
                                }
                            } else {
                                $callcompetency = DB::table('callcompetency')->insertGetId($Data);
                            }
                        }
                    }
    
                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "callcompetency",
                        'tableAutoId'    => $callcompetency,
                        'tableMainField'  => "CallCompetency",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                DB::commit();
                $save = $request->save;
                Session::flash('Success', 'Company Issue Type Created Successfully.');
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('component.index')->with('Success', 'Company Issue Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', 'Invlid Request');
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

    public function SupportType()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $supporttypes = SupportType::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->get();
            return view('wladmin.component.SupportType', compact('CompanyMaster', 'supporttypes'));
        } else {
            return redirect()->route('home');
        }
    }

    public function supporttypestore(Request $request)
    {
        if (Auth::User()->role_id == 2) {
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
                    
                        if ($strSupportType != "") {
                            if($support_type == 0){    
                            $Data = array(
                                'strSupportType' => $strSupportType,
                                "iCompanyId" => $request->company_id,
                                "strEntryDate" => date('Y-m-d H:i:s'),
                                "strIP" => $request->ip()
                            );
                            if ($request->iSuppotTypeId[$iCounter] != 0) {
                                $supporttype = $request->iSuppotTypeId[$iCounter];
                                if ($strSupportType != "") {
                                    DB::table('supporttype')->where("iSuppotTypeId", "=", $request->iSuppotTypeId[$iCounter])->update($Data);
                                } else {
                                    DB::table('supporttype')->where("iSuppotTypeId", "=", $request->iSuppotTypeId[$iCounter])->delete();
                                }
                            } else {
                                $supporttype = DB::table('supporttype')->insertGetId($Data);
                            }
                        }
                    }

                    $iCounter++;
                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "supporttype",
                        'tableAutoId'    => $supporttype,
                        'tableMainField'  => "SupportType",
                        'action'     => "Insert",
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                DB::commit();
                $save = $request->save;
                Session::flash('Success', 'Company Issue Type Created Successfully.');
                if ($save == '1') {
                    $company_id = $request->company_id;
                    echo $company_id;
                } else {
                    return redirect()->route('component.index')->with('Success', 'Company Issue Type Created Successfully.');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', 'Invlid Request');
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

    public function edit($id)
    {
        if (Auth::User()->role_id == 2) {
            $components = Component::select('component.*', 'system.strSystem as system')
                ->join('system', 'system.iSystemId', '=', 'component.strSystem')
                ->where(['component.isDelete' => 0, 'component.iStatus' => 1, 'iComponentId' => $id])
                ->first();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iComponentId" => $id])->get();
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => $components->iCompanyId])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            return view('wladmin.component.edit', compact('components', 'subcomponents', 'CompanyMaster'));
        } else {
            return redirect()->route('home');
        }
    }

    public function companycomponentupdate(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $iCounter = 0;

                $system = System::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id])
                    ->where('strSystem', 'like', trim($request->strSystem))->count();
                $iSystemId = 0;
                if (isset($system) && $system == 1) {
                    $systemId = System::where(['isDelete' => 0, 'iStatus' => 1,"iCompanyId" => $request->company_id])
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
                if($request->IsSubComponent == 1){
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
                            $sub_component = DB::table('subcomponent')->where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $request->company_id,"iComponentId" => $iComponentId])
                                ->where('strSubComponent', 'like', trim($request->strSubComponent[$iCounter]))->count();
                            if($sub_component == 0){
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
                } else {
                    $id = array();
                    foreach ($request->iSubComponentId as $iSubComponentId) {
                        array_push($id, $iSubComponentId);
                    }
                    
                    DB::table('subcomponent')->whereIn("iSubComponentId", $id)
                        ->where("iCompanyId", '=', $request->company_id)
                        ->where("iComponentId", '=', $iComponentId)
                        ->delete();
                }
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "component",
                    'tableAutoId'    => $iComponentId,
                    'tableMainField'  => "Component Update",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                infoTable::create($infoArr);

                return redirect()->route('component.index')->with('Success', 'Company Components Edited Successfully.');
            } catch (\Throwable $th) {
                Session::flash('Error', 'Invlid Request');
                DB::rollBack();
                return redirect()->back()->withInput()->with('Error', $th->getMessage());
            }
        } else {
            return redirect()->route('home');
        }
    }
    public function componentdelete(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $component = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "iComponentId" => $request->id])->first();
                DB::table('system')->where("iSystemId", "=", $component->strSystem)->delete();
                DB::table('subcomponent')->where("iComponentId", "=", $request->id)->delete();
                DB::table('component')->where("iComponentId", "=", $request->id)->delete();

                // $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                // $userdata = \App\Models\User::whereId($session)->first();
                // $infoArr = array(
                //     'tableName'    => "component",
                //     'tableAutoId'    => $id,
                //     'tableMainField'  => "Component Delete",
                //     'action'     => "Delete",
                //     'strEntryDate' => date("Y-m-d H:i:s"),
                //     'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                // );
                // infoTable::create($infoArr);
                Session::flash('Success', 'Company Component Deleted Successfully.');
                return redirect()->back();
            } catch (\Throwable $th) {
                Session::flash('Error', 'Invlid Request');
                DB::rollBack();
                return redirect()->back()->withInput()->with('Error', $th->getMessage());
            }
        } else {
            return redirect()->route('home');
        }
    }
}
