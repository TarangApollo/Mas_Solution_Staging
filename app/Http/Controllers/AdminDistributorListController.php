<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\DB;
use Auth;

class AdminDistributorListController extends Controller
{
    public function index(Request $request){
        if(Auth::User()->role_id == 1){
            $iDistributorId = $request->iDistributorId;
            $OEMCompany = $request->OEMCompany;
            $search_state = $request->search_state;
            $search_city = $request->search_city;
            
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();
            $distributors = Distributor::select('companydistributor.*', 'statemaster.strStateName', 'citymaster.strCityName',
                DB::raw("(select strOEMCompanyName from companymaster where companymaster.iCompanyId=companydistributor.iCompanyId) as 'strOEMCompanyName'")
                )->orderBy('iDistributorId', 'DESC')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'companydistributor.iStateId')
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'companydistributor.iCityId')
                ->when($request->iDistributorId, fn ($query, $iDistributorId) => $query->where('companydistributor.iDistributorId', $iDistributorId))
                ->when($request->OEMCompany, fn ($query, $OEMCompany) => $query->where('companydistributor.iCompanyId', $OEMCompany))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('companydistributor.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('companydistributor.iCityId', $search_city))
                ->where(['companydistributor.isDelete' => 0,"companydistributor.iStatus" => 1])
                ->get();
            $distributorlist = Distributor::where(['isDelete' => 0,"iStatus" => 1])->get();

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            return view('admin.distributor_list.index', compact('distributors','CompanyMaster','distributorlist','statemasters', 'citymasters', 'iDistributorId', 'OEMCompany','search_state', 'search_city'));
        } else {
            return redirect()->route('home');
        }
    }

    public function getCompanyDistributor(Request $request){
        
        $distributorlist = Distributor::where(['isDelete' => 0,'iStatus' => 1, 'iCompanyId' => $request->OEMCompany])->get();
        $html = "";
        if (count($distributorlist) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($distributorlist as $distributor) {
                $html .= '<option value="' . $distributor->iDistributorId . '">' . $distributor->Name . '</option>';
            }
        }else{
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    public function info(Request $request, $id){
        if(Auth::User()->role_id == 1){
            $CompanyClients = Distributor::where(['companydistributor.isDelete' => 0, 'companydistributor.iDistributorId' => $id])
                // ->join('icompanyclientprofile', 'icompanyclientprofile.iCompanyClientProfileId', '=', 'companydistributor.iCompanyClientProfileId', 'left outer')
                ->join('statemaster', 'statemaster.iStateId', '=', 'companydistributor.iStateId', ' left outer')
                ->join('companymaster', 'companymaster.iCompanyId', '=', 'companydistributor.iCompanyId', ' left outer')
                ->join('citymaster', 'citymaster.iCityId', '=', 'companydistributor.iCityId', ' left outer')
                ->join('userdefined', 'userdefined.iCompanyClientId', '=', 'companydistributor.iDistributorId', ' left outer')
                ->first();
            
            $salesperson = DB::table('salesperson')->where(['personType' => 2, "iCompanyClientId" => $id])->orderBy('iSalesId', 'DESC')->get();
            $technicalperson = DB::table('technicalperson')->where(['personType' => 2, "iCompanyClientId" => $id])->orderBy('iTechnicalId', 'DESC')->get();
            $CompanyClients['salesperson'] = $salesperson;
            $CompanyClients['technicalperson'] = $technicalperson;
            return view('admin.distributor_list.info', compact('CompanyClients'));
        } else {
            return redirect()->route('home');
        }
    }
    
    public function genrateDistributorExcel(Request $request){
        if (Auth::User()->role_id == 1) {
            $OEMCompanyID = $request->OEMCompany;
            // $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => $OEMCompanyID])
            //     ->orderBy('strOEMCompanyName', 'ASC')
            //     ->first();
            
            $distributors = Distributor::select('companydistributor.*', 'statemaster.strStateName', 'citymaster.strCityName')->orderBy('iDistributorId', 'DESC')
                ->leftjoin('statemaster', 'statemaster.iStateId', '=', 'companydistributor.iStateId')
                ->leftjoin('citymaster', 'citymaster.iCityId', '=', 'companydistributor.iCityId')
                ->when($request->OEMCompany, fn ($query, $OEMCompany) => $query->where('companydistributor.iCompanyId', $OEMCompany))
                ->when($request->iDistributorId, fn ($query, $iDistributorId) => $query->where('companydistributor.iDistributorId', $iDistributorId))
                ->when($request->search_state, fn ($query, $search_state) => $query->where('companydistributor.iStateId', $search_state))
                ->when($request->search_city, fn ($query, $search_city) => $query->where('companydistributor.iCityId', $search_city))
                ->where(['companydistributor.isDelete' => 0])
                ->get();
                
            return view('admin.distributor_list.download', compact('distributors'));
        } else {
            return redirect()->route('home');
        }
    }
}
