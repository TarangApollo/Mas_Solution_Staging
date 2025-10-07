<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallAttendent;
use App\Models\CompanyMaster;
use App\Models\Component;
use App\Models\CallCompetency;
use App\Models\ResolutionCategory;
use App\Models\System;
use App\Models\IssueType;
use App\Models\SubComponent;
use App\Models\SupportType;
use App\Models\ModuleMaster;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WLProfileController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            return view('wladmin.profile');
        } else {
            return redirect()->route('home');
        }
    }

    public function updateProfile(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $img = "";
            if ($request->hasFile('photo')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('photo');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/UserProfilePhoto/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
                $oldImg = $request->input('hiddenPhoto') ? $request->input('hiddenPhoto') : null;

                if ($oldImg != null || $oldImg != "") {
                    if (file_exists($destinationpath . $oldImg)) {
                        unlink($destinationpath . $oldImg);
                    }
                }
            } elseif ($request->input('hiddenPhoto')) {
                $oldImg = $request->input('hiddenPhoto');
                $img = $oldImg;
            } else {
                // $root = $_SERVER['DOCUMENT_ROOT'];
                // $img = $root . '/images/noimage.jpg';
                //   $img = null;
            }
            // DB::table('users')->where(['status' => 1, 'id' => auth()->user()->id])->delete();

            // $delete = DB::table('users')->where(['status' => 1, 'id' => auth()->user()->id])->first();

            // $root = $_SERVER['DOCUMENT_ROOT'];
            // $destinationpath = $root . '/ProfilePhoto/';

            // if ($delete->photo != "") {
            //     unlink($destinationpath . $delete->photo);
            // }

            $parts = explode(' ', $request->first_name);
            $firstname = array_shift($parts);
            $lastname = array_pop($parts);

            $Student = DB::table('users')
                ->where(['status' => 1, 'id' => auth()->user()->id])
                ->update([
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'photo' => $img,
                    'mobile_number' => $request->mobile_number,
                    'email' => $request->email,
                    'rows_visible' => $request->rows_visible,
                ]);

            return redirect()->back()->with('Success', 'Profile Updated Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function companyProfile()
    {
        $Company = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1, 'iCompanyId' => Session::get('CompanyId')])
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
        $moduleList=ModuleMaster::where('oem_company_modules.iOEMCompany', 6)
                    ->join('oem_company_modules', 'module_masters.id', '=', 'oem_company_modules.iModuleId')
                    ->get();
                    
        return view('wladmin.companyProfile', compact('Company', 'components', 'systems', 'supporttypes', 'callcompetencies', 'issuetypes', 'resolutionCategories','moduleList'));
    }
}
