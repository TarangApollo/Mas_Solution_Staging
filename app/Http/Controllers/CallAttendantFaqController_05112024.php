<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\CompanyMaster;
use App\Models\Component;
use App\Models\SubComponent;
use App\Models\System;
use App\Models\FaqDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Models\CallAttendent;
use App\Models\WlUser;


class CallAttendantFaqController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $userID = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                ->first();

            if (!$userID) {
                $userID = WlUser::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                    ->first();
            }
            $search_component = $request->iComponentId;
            $search_sub_component = $request->iSubComponentId;
            $search_system = $request->iSystemId;
            $search_company = $request->OemCompannyId;
            if (isset($request->searchFaq)) {
                $search_company = $request->OemCompannyId;
            } else if ($userID) {
                if (isset($userID->iCompanyId) && $userID->iCompanyId != "") {
                    $search_company = $userID->iCompanyId;
                }
                if (isset($userID->iOEMCompany) && $userID->iOEMCompany != "") {
                    $search_company = $userID->iOEMCompany;
                }
            } else {
                $search_company = 6;
            }
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])->orderBy('strSubComponent', 'ASC')
                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iComponentId, fn($query, $search_component) => $query->where('iComponentId', $search_component))
                ->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1])->orderBy('strComponent', 'ASC')
                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iSystemId, fn($query, $search_system) => $query->where('strSystem', $search_system))
                ->get();
            // $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
            //     ->orderBy('strOEMCompanyName', 'ASC')
            //     ->get();
            if ($userID) {
                // $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                //     ->when($search_company, fn ($query, $search_company) => $query->where('iCompanyId', $search_company))
                //     ->orderBy('strOEMCompanyName', 'ASC')
                //     ->get();
                if (isset($userID->iCompanyId) && $userID->iCompanyId == 0) {
                    $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                        ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                        ->whereIn('iCompanyId', function ($query) {
                            $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                        })
                        ->orderBy('strOEMCompanyName', 'ASC')
                        ->get();
                } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany == 0) {
                    $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                        ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                        ->whereIn('iCompanyId', function ($query) {
                            $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                        })
                        ->orderBy('strOEMCompanyName', 'ASC')
                        ->get();
                } else {
                    $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                        ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                        ->orderBy('strOEMCompanyName', 'ASC')
                        ->get();
                }
            } else {
                $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                    ->orderBy('strOEMCompanyName', 'ASC')
                    ->get();
            }
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1])->orderBy('strSystem', 'ASC')
                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->get();
            $Faqs = Faq::select('faqmaster.*', 'companymaster.strOEMCompanyName', 'component.strComponent', 'subcomponent.strSubComponent', 'system.strSystem')->orderBy('iFAQId', 'DESC')
                ->join('component', 'component.iComponentId', '=', 'faqmaster.iComponentId', 'left outer')
                ->join('subcomponent', 'subcomponent.iSubComponentId', '=', 'faqmaster.iSubComponentId', 'left outer')
                ->join('system', 'system.iSystemId', '=', 'faqmaster.iSystemId', 'left outer')
                ->join('companymaster', 'companymaster.iCompanyId', '=', 'faqmaster.iCompanyId', 'left outer')
                ->when($request->iComponentId, fn($query, $search_component) => $query->where('faqmaster.iComponentId', $search_component))
                ->when($request->iSubComponentId, fn($query, $search_sub_component) => $query->where('faqmaster.iSubComponentId', $search_sub_component))
                ->when($request->iSystemId, fn($query, $search_system) => $query->where('faqmaster.iSystemId', $search_system))
                ->when($search_company, fn($query, $search_company) => $query->where('faqmaster.iCompanyId', $search_company))
                ->when($request->searchText, fn($query, $searchText) => $query->where('faqmaster.strFAQTitle', "LIKE", "%" . $searchText . "%"))
                ->where(['faqmaster.isDelete' => 0, 'faqmaster.iStatus' => 1])->get();

            foreach ($Faqs as $faq) {
                $faqDocuments = FaqDocument::where(['isDelete' => 0, 'iStatus' => 1, 'iFAQId' => $faq->iFAQId])->orderBy('iFAQId', 'DESC')->get();
                $faq['gallery'] = $faqDocuments;
            }
            $postarray = array('OemCompannyId' => '6');
            foreach ($request->request as $key => $value) {

                $postarray[$key] = $value;
            }

            return view('call_attendant.Faq.index', compact('Faqs', 'componentLists', 'subcomponents', 'CompanyMaster', 'systemLists', 'postarray'));
        } else {
            return redirect()->route('home');
        }
    }
}
