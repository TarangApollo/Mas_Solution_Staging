<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\TicketMaster;
use App\Models\infoTable;
use App\Models\CallAttendent;
use Illuminate\Support\Facades\Mail;
use App\Models\CompanyInfo;
use App\Models\Distributor;
use App\Models\Rma;
use App\Models\RmaDetail;
use App\Models\RmaDocs;
use App\Models\System;
use Illuminate\Support\Facades\Auth;
use App\Models\WlUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\MultipleCompanyRole;

class RMAController extends Controller
{
     public function index(Request $request)
    {
        // try {
            if (Auth::User()->role_id == 3) {

                $session = Session::get('CompanyId');
                $userID = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                    ->first();
                if ($userID && $userID->iOEMCompany == 0) {
                    $userID = MultipleCompanyRole::where(['isDelete' => 0, 'iStatus' => 1, "userid" => Auth::user()->id])
                        ->first();
                }
                if (!$userID) {
                    $userID = WlUser::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                        ->first();
                }
                $ticketLists = TicketMaster::where(["iStatus" => 1, 'isDelete' => 0, 'finalStatus' => 4])
                    ->orderBy('iTicketId', 'asc');
                if ($userID) {
                    $ticketLists->when($userID->iCompanyId, fn($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId))
                        ->when($userID->iOEMCompany, fn($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId));
                }
                if (isset($userID->iCompanyId) && $userID->iCompanyId == 0) {
                    $ticketLists->whereIn('ticketmaster.OemCompannyId', function ($query) {
                        $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                    });
                } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany == 0) {
                    $ticketLists->whereIn('ticketmaster.OemCompannyId', function ($query) {
                        $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                    });
                }
                $ticketLists->whereNotIn('ticketmaster.iTicketId', function ($query) {
                    $query->select('iComplaintId')->from('rma');
                });
                $ticketList = $ticketLists->get();

                $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
                    ->orderBy('strSystem', 'asc')
                    ->get();

                $distributorList = Distributor::where(["iStatus" => 1, 'isDelete' => 0,"iCompanyId" => $userID->iOEMCompany])
                    ->orderBy('Name', 'asc')
                    ->get();

                $rmaCount = Rma::where(["iStatus" => 1, 'isDelete' => 0, "OemCompannyId" => Session::get('CompanyId')])->count();
                $iRMANumber = 'RN' . str_pad($rmaCount + 1, 4, '0', STR_PAD_LEFT); // Generate dynamic RMA number

                $rmaList = Rma::select(
                    'rma.*',
                    'companydistributor.Name as distributor_name',
                     DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma.strSelectSystem) as strSystem")
                )
                    ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, "OemCompannyId" => Session::get('CompanyId')])
                    ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                    ->orderBy('iRMANumber', 'desc')
                    ->get();

                    $groupedRmaList = [];
                    foreach ($rmaList as $rma) {
                        $rmaId = $rma->rma_id;
                        $rmadetail = DB::table('rma_detail')
                        ->select(
                            'rma_detail.*',
                            DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma_detail.strSelectSystem) as strSystem")
                        )
                        ->where('rma_id', $rmaId)
                        ->get();
                        $groupedRmaList[$rmaId] = [
                            'rma' => $rma,
                            'rma_details' => $rmadetail->isNotEmpty() ? $rmadetail : [],
                        ];
                    }


                    //->paginate(10);

                if (isset($request->OemCompannyId)) {
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

                if ($userID) {
                    // $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                    //     ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                    //     ->orderBy('strOEMCompanyName', 'ASC')
                    //     ->get();
                    if (isset($userID->iCompanyId) && $userID->iCompanyId != 0) {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            ->whereIn('iCompanyId', function ($query) {
                                $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                            })
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                        if ($CompanyMaster->isEmpty()) {
                            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                                ->orderBy('strOEMCompanyName', 'ASC')
                                ->get();
                        }
                    } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany != 0) {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            // ->whereIn('iCompanyId', function ($query) {
                            //     $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                            // })
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                    } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany == 0) {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            ->whereIn('iCompanyId', function ($query) {
                                $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                            })
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                    } else {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                    }
                } else {
                    $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                        ->orderBy('strOEMCompanyName', 'ASC')
                        ->get();
                }
                return view('call_attendant.rma.add', compact('groupedRmaList','CompanyMaster', 'ticketList', 'systemList', 'distributorList', 'iRMANumber', 'rmaList'));
            } else {
                return redirect()->route('home');
            }
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        // }
    }
    
    public function rmalist(Request $request)
    {
        try {
            if (Auth::User()->role_id == 3) {

                $session = Session::get('CompanyId');
                $userID = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                    ->first();
                if ($userID && $userID->iOEMCompany == 0) {
                    $userID = MultipleCompanyRole::where(['isDelete' => 0, 'iStatus' => 1, "userid" => Auth::user()->id])
                        ->first();
                }
                if (!$userID) {
                    $userID = WlUser::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                        ->first();
                }
                /*$ticketLists = TicketMaster::where(["iStatus" => 1, 'isDelete' => 0, 'finalStatus' => 4])
                    ->orderBy('iTicketId', 'asc');
                if ($userID) {
                    $ticketLists->when($userID->iCompanyId, fn($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId))
                        ->when($userID->iOEMCompany, fn($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId));
                }
                if (isset($userID->iCompanyId) && $userID->iCompanyId == 0) {
                    $ticketLists->whereIn('ticketmaster.OemCompannyId', function ($query) {
                        $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                    });
                } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany == 0) {
                    $ticketLists->whereIn('ticketmaster.OemCompannyId', function ($query) {
                        $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                    });
                }
                $ticketLists->whereNotIn('ticketmaster.iTicketId', function ($query) {
                    $query->select('iComplaintId')->from('rma');
                });
                $ticketList = $ticketLists->get();*/

                $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
                    ->orderBy('strSystem', 'asc')
                    ->get();

                $distributorList = Distributor::where(["iStatus" => 1, 'isDelete' => 0,"iCompanyId" => $userID->iOEMCompany])
                    ->orderBy('Name', 'asc')
                    ->get();

                $rmaCount = Rma::where(["iStatus" => 1, 'isDelete' => 0, "OemCompannyId" => Session::get('CompanyId')])->count();
                //$iRMANumber = 'RN' . str_pad($rmaCount + 1, 4, '0', STR_PAD_LEFT); // Generate dynamic RMA number
                $postarray = array(
                    "searchText" => $request->searchText,
                    "searchDistributor" => $request->searchDistributor,
                    "searchMonth" => $request->searchMonth,
                    "searchModelNo" => $request->searchModelNo,
                    "searchSystem" => $request->searchSystem,
                    "searchStatus" => $request->searchStatus,
                    "searchFactoryRmaNo" => $request->searchFactoryRmaNo,
                    "searchTestingResult" => $request->searchTestingResult
                );
                $rmaList = Rma::select(
                    'rma.*',
                    'companydistributor.Name as distributor_name',
                     DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma.strSelectSystem) as strSystem")
                )
                    ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, "OemCompannyId" => Session::get('CompanyId')])
                    ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                    ->orderBy('iRMANumber', 'desc')
                    ->when($request->searchText, fn ($query, $searchText) => $query->where('rma.iRMANumber', "like","%".$searchText."%"))
                    ->when($request->searchDistributor, fn ($query, $searchDistributor) => $query->where('rma.strDistributor', $searchDistributor))
                    ->when($request->searchModelNo, fn ($query, $searchModelNo) => $query->where('rma.strSerialNo', "like","%".$searchModelNo."%"))
                    ->when($request->searchSystem, fn ($query, $searchSystem) => $query->where('rma.strSelectSystem', $searchSystem))
                    ->when($request->searchStatus, fn ($query, $searchStatus) => $query->where('rma.strStatus', $searchStatus))
                    ->when($request->searchFactoryRmaNo, fn ($query, $searchFactoryRmaNo) => $query->where('rma.Factory_rma_no', $searchFactoryRmaNo))
                    ->when($request->searchTestingResult, fn ($query, $searchTestingResult) => $query->where('rma.Testing_result ', $searchTestingResult))
                    ->when($request->searchMonth, fn ($query, $searchMonth) => $query->whereMonth('rma.strRMARegistrationDate', $searchMonth))
                    ->get();

                    $groupedRmaList = [];
                    foreach ($rmaList as $rma) {
                        $rmaId = $rma->rma_id;
                        $rmadetail = DB::table('rma_detail')
                        ->select(
                            'rma_detail.*',
                            DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma_detail.strSelectSystem) as strSystem")
                        )
                        ->where('rma_id', $rmaId)
                        // ->when($request->searchText, fn ($query, $searchText) => $query->where('rma_detail.iRMANumber', "like","%".$searchText."%"))
                        // ->when($request->searchDistributor, fn ($query, $searchDistributor) => $query->where('rma_detail.strDistributor', $searchDistributor))
                        ->when($request->searchModelNo, fn ($query, $searchModelNo) => $query->where('rma_detail.strSerialNo', "like","%".$searchModelNo."%"))
                        ->when($request->searchSystem, fn ($query, $searchSystem) => $query->where('rma_detail.strSelectSystem', $searchSystem))
                        ->when($request->searchStatus, fn ($query, $searchStatus) => $query->where('rma_detail.strStatus', $searchStatus))
                        ->when($request->searchFactoryRmaNo, fn ($query, $searchFactoryRmaNo) => $query->where('rma_detail.Additional_Factory_rma_no', $searchFactoryRmaNo))
                        ->when($request->searchTestingResult, fn ($query, $searchTestingResult) => $query->where('rma_detail.Additional_Testing_result ', $searchTestingResult))
                        ->when($request->searchMonth, fn ($query, $searchMonth) => $query->whereMonth('rma_detail.strRMARegistrationDate', $searchMonth))
                        ->get();
                        $groupedRmaList[$rmaId] = [
                            'rma' => $rma,
                            'rma_details' => $rmadetail->isNotEmpty() ? $rmadetail : [],
                        ];
                    }

                    // dd($groupedRmaList);
                    //->paginate(10);

                if (isset($request->OemCompannyId)) {
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

                /*if ($userID) {
                    // $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                    //     ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                    //     ->orderBy('strOEMCompanyName', 'ASC')
                    //     ->get();
                    if (isset($userID->iCompanyId) && $userID->iCompanyId != 0) {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            ->whereIn('iCompanyId', function ($query) {
                                $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                            })
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                        if ($CompanyMaster->isEmpty()) {
                            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                                ->orderBy('strOEMCompanyName', 'ASC')
                                ->get();
                        }
                    } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany != 0) {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            // ->whereIn('iCompanyId', function ($query) {
                            //     $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                            // })
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                    } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany == 0) {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            ->whereIn('iCompanyId', function ($query) {
                                $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                            })
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                    } else {
                        $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                            //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                            ->orderBy('strOEMCompanyName', 'ASC')
                            ->get();
                    }
                } else {
                    $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                        ->orderBy('strOEMCompanyName', 'ASC')
                        ->get();
                }*/
                return view('call_attendant.rma.list', compact('groupedRmaList', 'systemList', 'distributorList','postarray', 'rmaList'));
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function get_details(Request $request)
    {
        try {

            $Tickets = TicketMaster::select(
                'ticketmaster.*',
                DB::raw('(SELECT companyclient.CompanyName FROM companyclient WHERE companyclient.iCompanyClientId=ticketmaster.iCompanyId) as CustomerCompany')
            )->where(["ticketmaster.iStatus" => 1, 'ticketmaster.isDelete' => 0, 'ticketmaster.iTicketId' => $request->iTicketId])
                ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "ticketmaster.iDistributorId")
                //->leftjoin('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iDistributorId")
                ->orderBy('iTicketId', 'asc');
            $Ticket = $Tickets->first();
            // Return response as JSON
            if ($Ticket) {
                return response()->json([
                    'ProjectName' => $Ticket->ProjectName,
                    'iDistributorId' => $Ticket->iDistributorId,
                    'CustomerName' => $Ticket->CustomerName,
                    'CustomerCompany' => $Ticket->CustomerCompany
                    // Add other fields if needed
                ]);
            }

            return response()->json([
                'error' => 'Ticket not found.',
            ], 404);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {

        try {
            if (Auth::User()->role_id == 3) {

                $rmaCount = Rma::where(["iStatus" => 1, 'isDelete' => 0, "OemCompannyId" => $request->OemCompannyId])->count();
               // Generate dynamic RMA number

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $companyid = DB::table('companymaster')->where('iCompanyId', $request->OemCompannyId)->get();
                //dd($companyid);
                $maxId = DB::table('rma')
                    ->where('OemCompannyId', $request->OemCompannyId)
                    ->max('iOEM_RMA_Id');


                $iRMANumber = "";
                if (isset($companyid[0]->strCompanyPrefix) && $companyid[0]->strCompanyPrefix != "") {

                    $iOEM_RMA_Id = $maxId ?? 0;
                    $iOEM_RMA_Id = $iOEM_RMA_Id + 1;
                    $iRMANumber = $companyid[0]->strCompanyPrefix . "-RN" . str_pad($iOEM_RMA_Id, 4, '0', STR_PAD_LEFT);
                }

                $array = array(
                    "iComplaintId" => $request->iComplaintId ?? 0,
                    "OemCompannyId" => $request->OemCompannyId,
                    "iOEM_RMA_Id" => $iOEM_RMA_Id ?? 0,
                    "iRMANumber" => $iRMANumber ?? 0,
                    "strCustomerCompany" => $request->strCustomerCompany,
                    "strCustomerEngineer" => $request->strCustomerEngineer,
                    "strDistributor" => $request->strDistributor,
                    "strProjectName" => $request->strProjectName,
                    "strRMARegistrationDate" => $request->strRMARegistrationDate
                        ? date('Y-m-d', strtotime($request->strRMARegistrationDate))
                        : Null,
                    "strItem" => $request->strItem,
                    "strItemDescription" => $request->strItemDescription,
                    "strSerialNo" => $request->strSerialNo,
                    "strDateCode" => $request->strDateCode,
                    "strInwarranty" => $request->strInwarranty,
                    "strQuantity" => $request->strQuantity,
                    "strSelectSystem" => $request->strSelectSystem,
                    "strFaultdescription" => $request->strFaultdescription,
                    "strFacts" => $request->strFacts,
                    "strAdditionalDetails" => $request->strAdditionalDetails,
                    "strMaterialReceived" => $request->strMaterialReceived,
                    "strMaterialReceivedDate" => $request->strMaterialReceivedDate
                        ? date('Y-m-d', strtotime($request->strMaterialReceivedDate))
                        : Null,
                    "strTesting" => $request->strTesting,
                    "strTestingCompleteDate" => $request->strTestingCompleteDate ? date('Y-m-d', strtotime($request->strTestingCompleteDate)) : Null,
                    "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                    "strReplacementApproved" => $request->strReplacementApproved,
                    "strReplacementReason" => $request->strReplacementReason,
                    "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                    "strFactory_MaterialReceivedDate" => $request->strFactory_MaterialReceivedDate ? date('Y-m-d', strtotime($request->strFactory_MaterialReceivedDate)) : Null,
                    "strFactory_Testing" => $request->strFactory_Testing,
                    "strFactory_TestingCompleteDate" => $request->strFactory_TestingCompleteDate ? date('Y-m-d', strtotime($request->strFactory_TestingCompleteDate)) : Null,
                    "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                    "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                    "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                    "strMaterialDispatched" => $request->strMaterialDispatched,
                    "strMaterialDispatchedDate" => $request->strMaterialDispatchedDate ? date('Y-m-d', strtotime($request->strMaterialDispatchedDate)) : Null,
                    "strStatus" => $request->strStatus,
                    "model_number" => $request->model_number,
                    "Testing_result" => $request->Testing_result,
                    "Testing_Comments" => $request->Testing_Comments,
                    "Factory_rma_no" => $request->Factory_rma_no,
                    "Factory_Status" => $request->Factory_Status,
                    "Factory_Comments" => $request->Factory_Comments,
                    "Cus_Comments" => $request->Cus_Comments,
                    "created_at" => now(),
                    "strIP" => $request->ip(),
                    "rma_enter_by" => Auth::User()->id,

                );

                $RMArecord =  Rma::Create($array);

                if ($request->hasfile('strImages')) {

                    foreach ($request->file('strImages') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();

                        $destinationpath = $root . "/RMADOC/images";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {
                            $Data = array(
                                "rma_id" =>  $RMArecord->id,
                                "rma_detail_id" =>  0,
                                "strImages" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('strVideos')) {

                    foreach ($request->file('strVideos') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/videos/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $RMArecord->id,
                                "rma_detail_id" =>  0,
                                "strVideos" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('strDocs')) {

                    foreach ($request->file('strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $RMArecord->id,
                                "rma_detail_id" =>  0,
                                "strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }
                if ($request->hasfile('Factory_strDocs')) {

                    foreach ($request->file('Factory_strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $RMArecord->id,
                                "rma_detail_id" =>  0,
                                "Factory_strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "rma",
                    'tableAutoId'    => $RMArecord->id,
                    'tableMainField'  => "RMA entered",
                    'action'     => "Inserted",
                    'strEntryDate' => now(),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);

                if($request->strStatus == "Closed"){
                    $Rma_info = array(
                        'tableName'       => "rma",
                        'tableAutoId'     => $RMArecord->id,
                        "strStatus"       => 'Open',
                        'tableMainField'  => "RMA entered",
                        'action'          => "Inserted",
                        'ram_detail_Id'   => 0,
                        'isShow'          => 1,
                        'strEntryDate'    => now(),
                        'created_at'      => now(),
                        'actionBy'        => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $rma_info = DB::table('rma_infolog')->insert($Rma_info);

                    $Rma_info = array(
                        'tableName'       => "rma",
                        'tableAutoId'     => $RMArecord->id,
                        "strStatus"       => $request->strStatus ?? 'Open',
                        'tableMainField'  => "RMA entered",
                        'action'          => "Inserted",
                        'ram_detail_Id'   => 0,
                        'isShow'          => 1,
                        'strEntryDate'    => now(),
                        'created_at'      => now(),
                        'actionBy'        => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $rma_info = DB::table('rma_infolog')->insert($Rma_info);
                } else {
                    $Rma_info = array(
                        'tableName'       => "rma",
                        'tableAutoId'     => $RMArecord->id,
                        "strStatus"       => $request->strStatus ?? 'Open',
                        'tableMainField'  => "RMA entered",
                        'action'          => "Inserted",
                        'ram_detail_Id'   => 0,
                        'isShow'          => 1,
                        'strEntryDate'    => now(),
                        'created_at'      => now(),
                        'actionBy'        => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $rma_info = DB::table('rma_infolog')->insert($Rma_info);
                }


                return redirect()->route('rma.index')->with('Success', 'RMA Created Successfully.');
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function additional_rma(Request $request, $id)
    {
        try {
            $session = Session::get('CompanyId');
            $rmaList = Rma::select(
                'rma.*',
                'companydistributor.Name as distributor_name',
                'ticketmaster.strTicketUniqueID'
            )
                ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, 'rma_id' => $id])
                ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                ->leftjoin('ticketmaster', "rma.iComplaintId", "=", "ticketmaster.iTicketId")
                ->orderBy('iRMANumber', 'asc')
                ->first();
            // dd($rmaList);

            $ticketList = TicketMaster::where(["iStatus" => 1, 'isDelete' => 0])
                ->orderBy('iTicketId', 'asc')
                ->get();

            $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
                ->orderBy('strSystem', 'asc')
                ->get();

            $distributorList = Distributor::where(["iStatus" => 1, 'isDelete' => 0,"iCompanyId" => $session])
                ->orderBy('Name', 'asc')
                ->get();


            return view('call_attendant.rma.additional_rma', compact('rmaList', 'ticketList', 'systemList', 'distributorList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function additional_rma_store(Request $request)
    {
        try {
            if (Auth::User()->role_id == 3) {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $array = array(
                    "rma_id" => $request->rma_id ?? 0,
                    "iRMANumber" => $request->iRMANumber ?? 0,
                    "strRMARegistrationDate" => $request->strRMARegistrationDate
                        ? date('Y-m-d', strtotime($request->strRMARegistrationDate))
                        : Null,
                    "strItem" => $request->strItem,
                    "strItemDescription" => $request->strItemDescription,
                    "strSerialNo" => $request->strSerialNo,
                    "strDateCode" => $request->strDateCode,
                    "strInwarranty" => $request->strInwarranty,
                    "strQuantity" => $request->strQuantity,
                    "strSelectSystem" => $request->strSelectSystem,
                    "strFaultdescription" => $request->strFaultdescription,
                    "strFacts" => $request->strFacts,
                    "strAdditionalDetails" => $request->strAdditionalDetails,
                    "strMaterialReceived" => $request->strMaterialReceived,
                    "strMaterialReceivedDate" => $request->strMaterialReceivedDate ? date('Y-m-d', strtotime($request->strMaterialReceivedDate)) : Null,
                    "strTesting" => $request->strTesting,
                    "strTestingCompleteDate" => $request->strTestingCompleteDate ? date('Y-m-d', strtotime($request->strTestingCompleteDate)) : Null,
                    "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                    "strReplacementApproved" => $request->strReplacementApproved,
                    "strReplacementReason" => $request->strReplacementReason,
                    "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                    "strFactory_MaterialReceivedDate" => $request->strFactory_MaterialReceivedDate ?  date('Y-m-d', strtotime($request->strFactory_MaterialReceivedDate)) : Null,
                    "strFactory_Testing" => $request->strFactory_Testing,
                    "strFactory_TestingCompleteDate" => $request->strFactory_TestingCompleteDate ?  date('Y-m-d', strtotime($request->strFactory_TestingCompleteDate)) : Null,
                    "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                    "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                    "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                    "strMaterialDispatched" => $request->strMaterialDispatched,
                    "strMaterialDispatchedDate" => $request->strMaterialDispatchedDate ?  date('Y-m-d', strtotime($request->strMaterialDispatchedDate)) : Null,
                    "strStatus" => $request->strStatus,
                    "created_at" => now(),
                    "strIP" => $request->ip(),
                    "rma_detail_enter_by" => Auth::User()->id,
                    "Additional_rma_model_number" => $request->Additional_rma_model_number,
                    "Additional_Testing_result" => $request->Additional_Testing_result,
                    "Additional_Testing_Comments" => $request->Additional_Testing_Comments,
                    "Additional_Factory_rma_no" => $request->Additional_Factory_rma_no,
                    "Additional_Factory_Status" => $request->Additional_Factory_Status,
                    "Additional_Factory_Comments" => $request->Additional_Factory_Comments,
                    "Additional_Cus_Comments" => $request->Additional_Cus_Comments,
                );

                $RMArecord =  RmaDetail::Create($array);

                if ($request->hasfile('strImages')) {

                    foreach ($request->file('strImages') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();

                        $destinationpath = $root . "/RMADOC/images";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {
                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" =>  $RMArecord->id,
                                "strImages" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('strVideos')) {

                    foreach ($request->file('strVideos') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/videos/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" =>  $RMArecord->id,
                                "strVideos" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('strDocs')) {

                    foreach ($request->file('strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" =>  $RMArecord->id,
                                "strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }
                if ($request->hasfile('Additional_Factory_strDocs')) {

                    foreach ($request->file('Additional_Factory_strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {
                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" => $RMArecord->id,
                                "strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "rma_detail",
                    'tableAutoId'    => $RMArecord->id,
                    'tableMainField'  => "RMA detail entered",
                    'action'     => "Inserted",
                    'strEntryDate' => now(),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);

                if($request->strStatus == "Closed"){

                    $rma_detail_info = array(
                        'tableName'    => "rma_detail",
                        'tableAutoId'    => $request->rma_id ?? 0,
                        'tableMainField'  => "RMA detail entered",
                        'action'     => "Inserted",
                        'ram_detail_Id'   => $RMArecord->id,
                        'isShow'          => 1,
                        'strEntryDate' => now(),
                        "strStatus"       => 'Open',
                        'created_at'      => now(),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $rma_info_detail = DB::table('rma_infolog')->insert($rma_detail_info);

                    $rma_detail_info = array(
                        'tableName'    => "rma_detail",
                        'tableAutoId'    => $request->rma_id ?? 0,
                        'tableMainField'  => "RMA detail entered",
                        'action'     => "Inserted",
                        'ram_detail_Id'   => $RMArecord->id,
                        'isShow'          => 1,
                        'strEntryDate' => now(),
                        "strStatus"       => $request->strStatus ?? 'Open',
                        'created_at'      => now(),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $rma_info_detail = DB::table('rma_infolog')->insert($rma_detail_info);
                } else {
                    $rma_detail_info = array(
                        'tableName'    => "rma_detail",
                        'tableAutoId'    => $request->rma_id ?? 0,
                        'tableMainField'  => "RMA detail entered",
                        'action'     => "Inserted",
                        'ram_detail_Id'   => $RMArecord->id,
                        'isShow'          => 1,
                        'strEntryDate' => now(),
                        "strStatus"       => $request->strStatus ?? 'Open',
                        'created_at'      => now(),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $rma_info_detail = DB::table('rma_infolog')->insert($rma_detail_info);
                }

                return redirect()->route('rma.index')->with('Success', 'RMA Detail Created Successfully.');
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function rma_info(Request $request, $id)
    {
        try {

            $session = Session::get('CompanyId');
            $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
                ->orderBy('strSystem', 'asc')
                ->get();

            $rmaList = Rma::select(
                'rma.*',
                'companydistributor.Name as distributor_name',
                'users.first_name',
                'users.last_name',
                'ticketmaster.strTicketUniqueID',
                'system.strSystem'
            )
                ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, 'rma_id' => $id])
                ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                ->leftjoin('users', "rma.rma_enter_by", "=", "users.id")
                ->leftjoin('ticketmaster', "rma.iComplaintId", "=", "ticketmaster.iTicketId")
                ->leftjoin('system', 'rma.strSelectSystem', '=', 'system.iSystemId')
                ->first();



            $rmadetailList = RmaDetail::select(
                'rma_detail.*',
                'system.iSystemId',
                'system.strSystem as system_name',
                'users.first_name',
                'users.last_name'
            )
                ->leftjoin('system', 'rma_detail.strSelectSystem', '=', 'system.iSystemId')
                ->leftjoin('users', "rma_detail.rma_detail_enter_by", "=", "users.id")
                ->where(["rma_detail.iStatus" => 1, 'rma_detail.isDelete' => 0, 'rma_id' => $id])
                ->get();

                 $Documents = RmaDocs::orderBy('rma_docs_id', 'desc')
                ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                ->where(function ($query) {
                    $query->whereNotNull('strImages')
                        ->orWhereNotNull('strVideos');
                })
                ->get();


            $Documents_files = RmaDocs::orderBy('rma_docs_id', 'desc')
                ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                ->where(function ($query) {
                    $query->whereNotNull('strDocs')
                        ->orWhereNotNull('Factory_strDocs');
                })
                ->get();


            // $Documents = RmaDocs::orderBy('rma_docs_id', 'desc')
            //     ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
            //     ->get();


            // $Rma_info_log = DB::table('rma_infolog')
            //     ->where('tableName', 'rma')
            //     ->where('isShow', 1)
            //     ->where('tableAutoId', $id)
            //     ->union(
            //         DB::table('rma_infolog')
            //             ->where('tableName', 'rma_detail')
            //             ->where('isShow', 1)
            //             ->where('tableAutoId', $id)
            //     )
            //     ->orderBy('id', 'ASC')
            //     ->get();

            $Rma_info_log = DB::table('rma_infolog')
            ->select(
            'rma_infolog.*',
            DB::raw("(SELECT strRMARegistrationDate FROM rma WHERE rma.rma_id = rma_infolog.tableAutoId AND
            rma_infolog.tableName = 'rma') as strRMARegistrationDate"),
            DB::raw("(SELECT strMaterialDispatchedDate FROM rma WHERE rma.rma_id = rma_infolog.tableAutoId AND rma_infolog.tableName = 'rma') as strMaterialDispatchedDate")
            )
            ->where('tableName', 'rma')
            ->where('isShow', 1)
            ->where('tableAutoId', $id)
            ->union(
            DB::table('rma_infolog')
            ->select(
            'rma_infolog.*',
            DB::raw("(SELECT strRMARegistrationDate FROM rma_detail WHERE rma_detail.rma_detail_id = rma_infolog.ram_detail_Id AND
            rma_infolog.tableName = 'rma_detail') as strRMARegistrationDate"),
             DB::raw("(SELECT strMaterialDispatchedDate FROM rma_detail WHERE rma_detail.rma_detail_id = rma_infolog.ram_detail_Id AND rma_infolog.tableName = 'rma_detail') as strMaterialDispatchedDate")
            )
            ->where('tableName', 'rma_detail')
            ->where('isShow', 1)
            ->where('tableAutoId', $id)
            )
            ->orderBy('id', 'ASC')
            ->get();
           

                $datalog = [];
                foreach ($Rma_info_log as $data) {
                    /*if ($data->ram_detail_Id > 0) {
                        $rma_detail_log = DB::table('rma_infolog')
                            ->where(['isShow' => 1, 'tableName' => 'rma_detail', 'ram_detail_Id' => $data->ram_detail_Id])
                            ->get();

                        if (count($rma_detail_log) >= 1) {
                            if ($data->strStatus != 'Open') {
                                $arr = [
                                    'tableName'      => $data->tableName,
                                    'tableAutoId'    => $data->tableAutoId,
                                    'ram_detail_Id'  => $data->ram_detail_Id,
                                    'strStatus'      => 'Open',
                                    'tableMainField' => $data->tableMainField,
                                    'action'         => $data->action,
                                    'isShow'         => $data->isShow,
                                    'actionBy'       => $data->actionBy,
                                    'strEntryDate'   => $data->strEntryDate,
                                ];
                                $datalog[] = (object)$arr; // Cast to object
                            }
                        }
                    }*/

                    $arr = [
                        'tableName'      => $data->tableName,
                        'tableAutoId'    => $data->tableAutoId,
                        'ram_detail_Id'  => $data->ram_detail_Id,
                        'strStatus'      => $data->strStatus,
                        'tableMainField' => $data->tableMainField,
                        'action'         => $data->action,
                        'isShow'         => $data->isShow,
                        'actionBy'       => $data->actionBy,
                        'strEntryDate'   => $data->strEntryDate,
                        'strRMARegistrationDate' => $data->strRMARegistrationDate,
                        'strMaterialDispatchedDate' => $data->strMaterialDispatchedDate,
                    ];
                    $datalog[] = (object)$arr; 
                }


            return view('call_attendant.rma.info', compact('Documents_files','datalog', 'rmaList', 'Documents', 'rmadetailList', 'id', 'systemList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function rma_edit(Request $request)
    {
        try {
            $session = Session::get('CompanyId');
            $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
                ->orderBy('strSystem', 'asc')
                ->get();
            $response = Rma::where('rma_id', $request->rma_id)->first();

            //--------------------------field 1 start ----------------------------
            $warrantyHtml = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strInwarranty == 'Yes') {
                $warrantyHtml .= '<option value="Yes" selected>Yes</option>';
            } else {
                $warrantyHtml .= '<option value="Yes">Yes</option>';
            }
            if ($response->strInwarranty == 'No') {
                $warrantyHtml .= '<option value="No" selected>No</option>';
            } else {
                $warrantyHtml .= '<option value="No">No</option>';
            }
            //--------------------------field 1 end ----------------------------

            //--------------------------field 2 start ----------------------------
            $Materialhtml = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strMaterialReceived == 'Yes') {
                $Materialhtml .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Materialhtml .= '<option value="Yes">Yes</option>';
            }
            if ($response->strMaterialReceived == 'No') {
                $Materialhtml .= '<option value="No" selected>No</option>';
            } else {
                $Materialhtml .= '<option value="No">No</option>';
            }
            //--------------------------field 2 end ----------------------------

            //--------------------------field 3 start ----------------------------
            $Testinghtml = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strTesting == 'Done') {
                $Testinghtml .= '<option value="Done" selected>Done</option>';
            } else {
                $Testinghtml .= '<option value="Done">Done</option>';
            }
            if ($response->strTesting == 'In Progress') {
                $Testinghtml .= '<option value="In Progress" selected>In Progress</option>';
            } else {
                $Testinghtml .= '<option value="In Progress">In Progress</option>';
            }
            //--------------------------field 3 end ----------------------------
            //--------------------------field 4 start ----------------------------
            $Fault_Covered_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strFaultCoveredinWarranty == 'Yes') {
                $Fault_Covered_html .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Fault_Covered_html .= '<option value="Yes">Yes</option>';
            }
            if ($response->strFaultCoveredinWarranty == 'No') {
                $Fault_Covered_html .= '<option value="No" selected>No</option>';
            } else {
                $Fault_Covered_html .= '<option value="No">No</option>';
            }
            //--------------------------field 4 end ----------------------------
            //--------------------------field 5 start ----------------------------
            $Replacement_Approved_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strReplacementApproved == 'Yes') {
                $Replacement_Approved_html .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Replacement_Approved_html .= '<option value="Yes">Yes</option>';
            }
            if ($response->strReplacementApproved == 'No') {
                $Replacement_Approved_html .= '<option value="No" selected>No</option>';
            } else {
                $Replacement_Approved_html .= '<option value="No">No</option>';
            }
            //--------------------------field 5 end ----------------------------
            //--------------------------field 6 start ----------------------------
            $Replacement_Reason_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strReplacementReason == 'Warranty') {
                $Replacement_Reason_html .= '<option value="Warranty" selected>Warranty</option>';
            } else {
                $Replacement_Reason_html .= '<option value="Warranty">Warranty</option>';
            }
            if ($response->strReplacementReason == 'Sales Call') {
                $Replacement_Reason_html .= '<option value="Sales Call" selected>Sales Call</option>';
            } else {
                $Replacement_Reason_html .= '<option value="Sales Call">Sales Call</option>';
            }
            //--------------------------field 6 end ----------------------------
            //--------------------------field 7 start ----------------------------
            $Material_Received_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strFactory_MaterialReceived == 'Yes') {
                $Material_Received_html .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Material_Received_html .= '<option value="Yes">Yes</option>';
            }
            if ($response->strFactory_MaterialReceived == 'No') {
                $Material_Received_html .= '<option value="No" selected>No</option>';
            } else {
                $Material_Received_html .= '<option value="No">No</option>';
            }
            //--------------------------field 7 end ----------------------------
            //--------------------------field 8 start ----------------------------
            $Factory_Testing_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strFactory_Testing == 'Done') {
                $Factory_Testing_html .= '<option value="Done" selected>Done</option>';
            } else {
                $Factory_Testing_html .= '<option value="Done">Done</option>';
            }
            if ($response->strFactory_Testing == 'In Progress') {
                $Factory_Testing_html .= '<option value="In Progress" selected>In Progress</option>';
            } else {
                $Factory_Testing_html .= '<option value="In Progress">In Progress</option>';
            }
            //--------------------------field 8 end ----------------------------
            //--------------------------field 9 start ----------------------------
            $Fault_Covered_in_Warranty = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strFactory_FaultCoveredinWarranty == 'Yes') {
                $Fault_Covered_in_Warranty .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Fault_Covered_in_Warranty .= '<option value="Yes">Yes</option>';
            }
            if ($response->strFactory_FaultCoveredinWarranty == 'No') {
                $Fault_Covered_in_Warranty .= '<option value="No" selected>No</option>';
            } else {
                $Fault_Covered_in_Warranty .= '<option value="No">No</option>';
            }
            //--------------------------field 9 end ----------------------------
            //--------------------------field 10 start ----------------------------
            $Factory_ReplacementApproved = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strFactory_ReplacementApproved == 'Yes') {
                $Factory_ReplacementApproved .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Factory_ReplacementApproved .= '<option value="Yes">Yes</option>';
            }
            if ($response->strFactory_ReplacementApproved == 'No') {
                $Factory_ReplacementApproved .= '<option value="No" selected>No</option>';
            } else {
                $Factory_ReplacementApproved .= '<option value="No">No</option>';
            }
            //--------------------------field 10 end ----------------------------
            //--------------------------field 11 start ----------------------------
            $Factory_Replacement_Reason_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strFactory_ReplacementReason == 'Warranty') {
                $Factory_Replacement_Reason_html .= '<option value="Warranty" selected>Warranty</option>';
            } else {
                $Factory_Replacement_Reason_html .= '<option value="Warranty">Warranty</option>';
            }
            if ($response->strFactory_ReplacementReason == 'Sales Call') {
                $Factory_Replacement_Reason_html .= '<option value="Sales Call" selected>Sales Call</option>';
            } else {
                $Factory_Replacement_Reason_html .= '<option value="Sales Call">Sales Call</option>';
            }
            //--------------------------field 11 end ----------------------------

            //--------------------------field 12 start ----------------------------
            $Material_Dispatched = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strMaterialDispatched == 'Yes') {
                $Material_Dispatched .= '<option value="Yes" selected>Yes</option>';
            } else {
                $Material_Dispatched .= '<option value="Yes">Yes</option>';
            }
            if ($response->strMaterialDispatched == 'No') {
                $Material_Dispatched .= '<option value="No" selected>No</option>';
            } else {
                $Material_Dispatched .= '<option value="No">No</option>';
            }
            //--------------------------field 12 end ----------------------------
            //--------------------------field 13 start ----------------------------
            $cus_Status = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->strStatus == 'Open') {
                $cus_Status .= '<option value="Open" selected>Open</option>';
            } else {
                $cus_Status .= '<option value="Open" selected>Open</option>';
            }
            if ($response->strStatus == 'Closed') {
                $cus_Status .= '<option value="Closed" selected>Closed</option>';
            } else {
                $cus_Status .= '<option value="Closed">Closed</option>';
            }
            //--------------------------field 13 end ----------------------------
            //--------------------------field 14 start ----------------------------
            $Testing_Result_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->Testing_result == 'No Fault Found') {
                $Testing_Result_html .= '<option value="No Fault Found" selected>No Fault Found</option>';
            } else {
                $Testing_Result_html .= '<option value="No Fault Found">No Fault Found</option>';
            }
            if ($response->Testing_result == 'Customer Liability') {
                $Testing_Result_html .= '<option value="Customer Liability" selected>Customer Liability</option>';
            } else {
                $Testing_Result_html .= '<option value="Customer Liability">Customer Liability</option>';
            }
            if ($response->Testing_result == 'Product Failure') {
                $Testing_Result_html .= '<option value="Product Failure" selected>Product Failure</option>';
            } else {
                $Testing_Result_html .= '<option value="Product Failure">Product Failure</option>';
            }
            if ($response->Testing_result == 'Programming Issue') {
                $Testing_Result_html .= '<option value="Programming Issue" selected>Programming Issue</option>';
            } else {
                $Testing_Result_html .= '<option value="Programming Issue">Programming Issue</option>';
            }
             if ($response->Testing_result == 'Repair Locally') {
                $Testing_Result_html .= '<option value="Repair Locally" selected>Repair Locally</option>';
            } else {
                $Testing_Result_html .= '<option value="Repair Locally">Repair Locally</option>';
            }

            //--------------------------field 14 end ----------------------------
            //--------------------------field 15 start ----------------------------
            $HFI_Status_html = '<option label="Please Select" value="">-- Select --</option>';
            if ($response->Factory_Status == 'Open') {
                $HFI_Status_html .= '<option value="Open" selected>Open</option>';
            } else {
                $HFI_Status_html .= '<option value="Closed">Closed</option>';
            }
            //--------------------------field 15 end ----------------------------

            $systemhtml = '<option label="Please Select" value="">-- Select --</option>';
            foreach ($systemList as $system) {
                if ($response->strSelectSystem == $system->iSystemId) {
                    $systemhtml .= '<option value="' . $system->iSystemId . '" selected>' . $system->strSystem . '</option>';
                } else {
                    $systemhtml .= '<option value="' . $system->iSystemId . '">' . $system->strSystem . '</option>';
                }
            }


            if ($response) {
                return response()->json([
                    $response,
                    $warrantyHtml,
                    $Materialhtml,
                    $systemhtml,
                    $Testinghtml,
                    $Fault_Covered_html,
                    $Replacement_Approved_html,
                    $Replacement_Reason_html,
                    $Material_Received_html,
                    $Factory_Testing_html,
                    $Fault_Covered_in_Warranty,
                    $Factory_ReplacementApproved,
                    $Factory_Replacement_Reason_html,
                    $Material_Dispatched,
                    $cus_Status,
                    $Testing_Result_html,
                    $HFI_Status_html
                ]);
            }
            return response()->json([
                'error' => 'RMA record not found'
            ], 404);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function rma_update(Request $request)
    {

        try {
            if (Auth::User()->role_id == 3) {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $array = array(
                    "strRMARegistrationDate" => $request->strRMARegistrationDate ? date('Y-m-d', strtotime($request->strRMARegistrationDate)) : Null,
                    "strItem" => $request->Item,
                    "strItemDescription" => $request->strItemDescription,
                    "strSerialNo" => $request->strSerialNo,
                    "strDateCode" => $request->strDateCode,
                    "strInwarranty" => $request->strInwarranty,
                    "strQuantity" => $request->strQuantity,
                    "strSelectSystem" => $request->strSelectSystem,
                    "strFaultdescription" => $request->strFaultdescription,
                    "strFacts" => $request->strFacts,
                    "strAdditionalDetails" => $request->strAdditionalDetails,
                    "strMaterialReceived" => $request->strMaterialReceived,
                    "strMaterialReceivedDate" => $request->Material_Received_Date ? date('Y-m-d', strtotime($request->Material_Received_Date)) : Null,
                    "strTesting" => $request->strTesting,
                    "strTestingCompleteDate" => $request->strTestingCompleteDate ? date('Y-m-d', strtotime($request->strTestingCompleteDate)) : Null,
                    "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                    "strReplacementApproved" => $request->strReplacementApproved,
                    "strReplacementReason" => $request->strReplacementReason,
                    "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                    "strFactory_MaterialReceivedDate" => $request->Factory_Material_Received_Date ? date('Y-m-d', strtotime($request->Factory_Material_Received_Date)) : Null,
                    "strFactory_Testing" => $request->strFactory_Testing,
                    "strFactory_TestingCompleteDate" => $request->strFactory_TestingCompleteDate,
                    "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                    "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                    "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                    "strMaterialDispatched" => $request->strMaterialDispatched,
                    "strMaterialDispatchedDate" => $request->strMaterialDispatchedDate ? date('Y-m-d', strtotime($request->strMaterialDispatchedDate)) : Null,
                    "strStatus" => $request->strStatus,
                    "updated_at" => now(),
                    "strIP" => $request->ip(),
                    "rma_enter_by" => Auth::User()->id,
                    "model_number" => $request->model_number,
                    "Testing_result" => $request->Testing_result,
                    "Testing_Comments" => $request->Testing_Comments,
                    "Factory_rma_no" => $request->Factory_rma_no,
                    "Factory_Status" => $request->Factory_Status,
                    "Factory_Comments" => $request->Factory_Comments,
                    "Cus_Comments" => $request->Cus_Comments,
                );

                $RMArecord =  DB::table('rma')->where('rma_id', $request->rma_id)->update($array);

                if ($request->hasfile('strImages')) {
                    foreach ($request->file('strImages') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();

                        $destinationpath = $root . "/RMADOC/images";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {
                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" => 0,
                                "strImages" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );

                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }
                
                if ($request->hasfile('strVideos')) {
                    foreach ($request->file('strVideos') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/videos/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" => 0,
                                "strVideos" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }
                
                if ($request->hasfile('strDocs')) {
                    foreach ($request->file('strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $request->rma_id,
                                "rma_detail_id" => 0,
                                "strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }
                
                if ($request->hasfile('Factory_strDocs')) {

                    foreach ($request->file('Factory_strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" => $request->rma_id,
                                "rma_detail_id" =>  0,
                                "Factory_strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }
                
                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "rma",
                    'tableAutoId'    => $request->rma_id,
                    'tableMainField'  => "RMA update",
                    'action'     => "updated",
                    'strEntryDate' => now(),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);

                $getrecord = DB::table('rma_infolog')
                ->where('tableAutoId', $request->rma_id)
                //->where('action', 'updated')
                ->where('tableName', 'rma')
                ->where('isShow', 1)
                ->where('strStatus','Closed')
                ->first();
                
                if ($getrecord) {
                    DB::table('rma_infolog')
                    ->where('tableAutoId', $request->rma_id)
                    //->where('action', 'updated')
                    ->where('strStatus','Closed')
                    ->where('tableName', 'rma')
                    ->where('isShow', 1)
                    ->update(['isShow' => 0]);
                }
                $isShow = 0;
                if($request->strStatus == 'Closed'){
                    $isShow = 1;
                } else {
                    $isShow = 0;
                }
                $Rma_info = array(
                    'tableName'       => "rma",
                    'tableAutoId'     => $request->rma_id,
                    "strStatus"       => $request->strStatus ?? 'Open',
                    'tableMainField'  => "RMA update",
                    'action'          => "updated",
                    'ram_detail_Id'   => 0,
                    'isShow'          => $isShow,
                    'strEntryDate'    => now(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                    'actionBy'        => $userdata->first_name . " " . $userdata->last_name,
                );
                $rma_info = DB::table('rma_infolog')->insert($Rma_info);

                return back()->with('Success', 'RMA updated successfully.');
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function rma_detail_update(Request $request)
    {
        try {

            if (Auth::User()->role_id == 3) {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $Get_rma_id =  DB::table('rma_detail')->select('rma_id')
                    ->where('rma_detail_id', $request->rma_detail_id)->first();

                $array = array(
                    "strRMARegistrationDate" => $request->strRMARegistrationDate ? date('Y-m-d', strtotime($request->strRMARegistrationDate)) : Null,
                    "strItem" => $request->Item,
                    "strItemDescription" => $request->strItemDescription,
                    "strSerialNo" => $request->strSerialNo,
                    "strDateCode" => $request->strDateCode,
                    "strInwarranty" => $request->strInwarranty,
                    "strQuantity" => $request->strQuantity,
                    "strSelectSystem" => $request->strSelectSystem,
                    "strFaultdescription" => $request->strFaultdescription,
                    "strFacts" => $request->strFacts,
                    "strAdditionalDetails" => $request->strAdditionalDetails,
                    "strMaterialReceived" => $request->strMaterialReceived,
                    "strMaterialReceivedDate" => $request->Material_Received_Date ? date('Y-m-d', strtotime($request->Material_Received_Date)) : Null,
                    "strTesting" => $request->strTesting,
                    "strTestingCompleteDate" => $request->strTestingCompleteDate  ? date('Y-m-d', strtotime($request->strTestingCompleteDate)) : Null,
                    "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                    "strReplacementApproved" => $request->strReplacementApproved,
                    "strReplacementReason" => $request->strReplacementReason,
                    "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                    "strFactory_MaterialReceivedDate" => $request->Factory_Material_Received_Date ? date('Y-m-d', strtotime($request->Factory_Material_Received_Date)) : Null,
                    "strFactory_Testing" => $request->strFactory_Testing,
                    "strFactory_TestingCompleteDate" => $request->strFactory_TestingCompleteDate ? date('Y-m-d', strtotime($request->strFactory_TestingCompleteDate)) : Null,
                    "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                    "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                    "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                    "strMaterialDispatched" => $request->strMaterialDispatched,
                    "strMaterialDispatchedDate" => $request->strMaterialDispatchedDate ? date('Y-m-d', strtotime($request->strMaterialDispatchedDate)) : Null,
                    "strStatus" => $request->strStatus,
                    "created_at" => now(),
                    "strIP" => $request->ip(),
                    "rma_detail_enter_by" => Auth::User()->id,
                    // "model_number" => $request->model_number,
                    "Additional_Testing_result" => $request->Additional_Testing_result,
                    "Additional_Testing_Comments" => $request->Additional_Testing_Comments,
                    "Additional_Factory_rma_no" => $request->Additional_Factory_rma_no,
                    "Additional_Factory_Status" => $request->Additional_Factory_Status,
                    "Additional_Factory_Comments" => $request->Additional_Factory_Comments,
                    "Additional_Cus_Comments" => $request->Additional_Cus_Comments
                );

                $RMArecord =  DB::table('rma_detail')->where('rma_detail_id', $request->rma_detail_id)->update($array);
                if ($request->hasfile('strImages')) {

                    foreach ($request->file('strImages') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();

                        $destinationpath = $root . "/RMADOC/images";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {
                            $Data = array(
                                "rma_id" =>  $Get_rma_id->rma_id,
                                "rma_detail_id" => $request->rma_detail_id,
                                "strImages" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('strVideos')) {

                    foreach ($request->file('strVideos') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/videos/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $Get_rma_id->rma_id,
                                "rma_detail_id" => $request->rma_detail_id,
                                "strVideos" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('strDocs')) {

                    foreach ($request->file('strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $Get_rma_id->rma_id,
                                "rma_detail_id" => $request->rma_detail_id,
                                "strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                if ($request->hasfile('Factory_strDocs')) {

                    foreach ($request->file('Factory_strDocs') as $file) {
                        $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationpath = $root . "/RMADOC/docs/";
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        if ($file->move($destinationpath, $name)) {

                            $Data = array(
                                "rma_id" =>  $Get_rma_id->rma_id,
                                "rma_detail_id" => $request->rma_detail_id,
                                "Factory_strDocs" => $name,
                                "created_at" => now(),
                                "strIP" => $request->ip(),
                            );
                            RmaDocs::create($Data);
                        } else {
                            $iStatus = 0;
                        }
                    }
                }

                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "rma_detail",
                    'tableAutoId'    => $request->rma_detail_id,
                    'tableMainField'  => "RMA detail entered",
                    'action'     => "updated",
                    'strEntryDate' => now(),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);

                $getrecord = DB::table('rma_infolog')
                ->where('ram_detail_Id', $request->rma_detail_id)
                //->where('action', 'updated')
                ->where('tableName', 'rma_detail')
                ->where('isShow', 1)
                ->where('strStatus','Closed')
                ->first();
                if ($getrecord) {
                    DB::table('rma_infolog')
                    ->where('ram_detail_Id', $request->rma_detail_id)
                    //->where('action', 'updated')
                    ->where('strStatus','Closed')
                    ->where('tableName', 'rma_detail')
                    ->where('isShow', 1)
                    ->update(['isShow' => 0]);
                }
                $isShow = 0;
                if($request->strStatus == 'Closed'){
                    $isShow = 1;
                } else {
                    $isShow = 0;
                }
                $rma_detail_info = array(
                    'tableName'    => "rma_detail",
                    'tableAutoId'    => $Get_rma_id->rma_id,
                    'tableMainField'  => "RMA detail entered",
                    'action'     => "updated",
                    'strEntryDate' => now(),
                    "strStatus"       => $request->strStatus ?? 'Open',
                    'ram_detail_Id'   => $request->rma_detail_id,
                    'isShow'          => $isShow,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $rma_info_detail = DB::table('rma_infolog')->insert($rma_detail_info);

                return back()->with('Success', 'RMA Detail Updated Successfully.');
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function rma_image_delete(Request $request, $id = null)
    {
        try {
            $rma_delete = DB::table('rma_docs')->where('rma_docs_id', $id)->delete();
            return back()->with('Success', 'Deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function rma_detail_delete(Request $request, $id = null)
    {
        try {
            $rma_detail_delete = DB::table('rma_docs')->where('rma_docs_id', $id)->delete();
            return back()->with('Success', 'Deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
     public function wl_index(Request $request)
    {

        // try {

            if (Auth::User()->role_id == 2)
            {
                $search_Customer_Name_and_RMA_No = $request->Customer_Name_and_RMA_No ?? '';
                $search_Customer_Status = $request->Customer_Status ?? '';
                $HFI = $request->HFI ?? '';

                $searchYear = $request->fYear ?? null;
                $datefrom = "";
                $dateto = "";
                if (isset($request->fYear) && $request->fYear != "") {
                    $yeardetail = DB::table('yearlog')->where('iYearId', $request->fYear)->first();
                    $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
                    $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
                }

                $searchMonth = $request->fMonth ?? null;

                $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();
                $rmsDatas = DB::table('rma')
                    ->select(
                        'rma.*',
                        'ticketmaster.CustomerName',
                    DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma.strSelectSystem) as strSystem")
                    )
                    ->leftJoin('ticketmaster', 'rma.iComplaintId', '=', 'ticketmaster.iTicketId')
                    ->where("rma.OemCompannyId","=", Session::get('CompanyId'))
                    ->when($search_Customer_Name_and_RMA_No, function ($query, $search) {
                        $query->where('ticketmaster.CustomerName', 'LIKE', "%{$search}%")
                            ->orWhere('rma.iRMANumber', 'LIKE', "%{$search}%");
                    })
                    ->when($search_Customer_Status, function ($query, $status) {
                        $query->where('rma.strStatus', $status)
                            ->orWhereIn('rma.rma_id', function ($subquery, $status) {
                              $subquery->select('rma_id')
                                       ->from('rma_detail')
                                       ->where('strStatus', $status);
                            });
                    })
                    ->when($HFI, function ($query, $HFI) {
                        $query->where('rma.Factory_Status', $HFI)
                            ->orWhereIn('rma.rma_id', function ($subquery) use($HFI) {
                                $subquery->select('rma_id')
                                       ->from('rma_detail')
                                       ->where('Additional_Factory_Status', $HFI);
                            });
                    });

                if (isset($request->fYear) && $request->fYear != "") {
                    $rmsDatas->whereBetween('rma.created_at', [$datefrom, $dateto]);
                }
                $rmsDatas->when($searchMonth, function ($query, $month) {
                    $query->whereMonth('rma.created_at', $month);
                })
                ->orderBy('rma.rma_id', 'desc');
                // dd($rmsDatas->toSql(),$rmsDatas->getBindings());
                $rmsData = $rmsDatas->get();
                
                $groupedRmadetailList = [];
                foreach ($rmsData as $rma) {
                    $rmaId = $rma->rma_id;
                    $rmadetail = DB::table('rma_detail')
                        ->select(
                            'rma_detail.*',
                            DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma_detail.strSelectSystem) as strSystem")
                        )
                        ->where('rma_id', $rmaId)
                        ->get()->toArray();

                    $groupedRmadetailList[$rmaId] = (array)$rma;
                    $groupedRmadetailList[$rmaId]['rma_details'] = !empty($rmadetail) ? (array)$rmadetail : [];
                }


                return view('wladmin.Wl_RMA.index', compact('groupedRmadetailList','HFI', 'searchMonth', 'searchYear', 'yearList', 'rmsData', 'search_Customer_Name_and_RMA_No', 'search_Customer_Status'));
            } else {
                return redirect()->route('home');
            }
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        // }
    }
    public function rma_summary_info(Request $request, $id = null)
    {
        try {

            $rmaList = Rma::select(
                'rma.*',
                'companydistributor.Name as distributor_name',
                'users.first_name',
                'users.last_name',
                'ticketmaster.strTicketUniqueID',
                'system.strSystem'
            )
                ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, 'rma_id' => $id])
                ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                ->leftjoin('users', "rma.rma_enter_by", "=", "users.id")
                ->leftjoin('ticketmaster', "rma.iComplaintId", "=", "ticketmaster.iTicketId")
                ->leftjoin('system', 'rma.strSelectSystem', '=', 'system.iSystemId')
                ->first();


            $rmadetailList = RmaDetail::select(
                'rma_detail.*',
                'system.iSystemId',
                'system.strSystem as system_name',
                'users.first_name',
                'users.last_name'
            )
                ->leftjoin('system', 'rma_detail.strSelectSystem', '=', 'system.iSystemId')
                ->leftjoin('users', "rma_detail.rma_detail_enter_by", "=", "users.id")
                ->where(["rma_detail.iStatus" => 1, 'rma_detail.isDelete' => 0, 'rma_id' => $id])
                ->get();

            // $Documents = RmaDocs::orderBy('rma_docs_id', 'desc')
            //     ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
            //     ->get();
            $Documents = RmaDocs::orderBy('rma_docs_id', 'desc')
                ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                ->where(function ($query) {
                    $query->whereNotNull('strImages')
                        ->orWhereNotNull('strVideos');
                })
                ->get();


            $Documents_files = RmaDocs::orderBy('rma_docs_id', 'desc')
                ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                ->where(function ($query) {
                    $query->whereNotNull('strDocs')
                        ->orWhereNotNull('Factory_strDocs');
                })
                ->get();



            $Rma_info_log = DB::table('rma_infolog')
            ->select(
            'rma_infolog.*',
            DB::raw("(SELECT strRMARegistrationDate FROM rma WHERE rma.rma_id = rma_infolog.tableAutoId AND
            rma_infolog.tableName = 'rma') as strRMARegistrationDate"),
            DB::raw("(SELECT strMaterialDispatchedDate FROM rma WHERE rma.rma_id = rma_infolog.tableAutoId AND rma_infolog.tableName = 'rma') as strMaterialDispatchedDate")
            )
            ->where('tableName', 'rma')
            ->where('isShow', 1)
            ->where('tableAutoId', $id)
            ->union(
            DB::table('rma_infolog')
            ->select(
            'rma_infolog.*',
            DB::raw("(SELECT strRMARegistrationDate FROM rma_detail WHERE rma_detail.rma_detail_id = rma_infolog.ram_detail_Id AND
            rma_infolog.tableName = 'rma_detail') as strRMARegistrationDate"),
             DB::raw("(SELECT strMaterialDispatchedDate FROM rma_detail WHERE rma_detail.rma_detail_id = rma_infolog.ram_detail_Id AND rma_infolog.tableName = 'rma_detail') as strMaterialDispatchedDate")
            )
            ->where('tableName', 'rma_detail')
            ->where('isShow', 1)
            ->where('tableAutoId', $id)
            )
            ->orderBy('id', 'ASC')
            ->get();

                $datalog = [];
                foreach ($Rma_info_log as $data) {
                    /*if ($data->ram_detail_Id > 0) {
                        $rma_detail_log = DB::table('rma_infolog')
                            ->where(['isShow' => 1, 'tableName' => 'rma_detail', 'ram_detail_Id' => $data->ram_detail_Id])
                            ->get();

                        if (count($rma_detail_log) >= 1) {
                            if ($data->strStatus != 'Open') {
                                $arr = [
                                    'tableName'      => $data->tableName,
                                    'tableAutoId'    => $data->tableAutoId,
                                    'ram_detail_Id'  => $data->ram_detail_Id,
                                    'strStatus'      => 'Open',
                                    'tableMainField' => $data->tableMainField,
                                    'action'         => $data->action,
                                    'isShow'         => $data->isShow,
                                    'actionBy'       => $data->actionBy,
                                    'strEntryDate'   => $data->strEntryDate,
                                ];
                                $datalog[] = (object)$arr; // Cast to object
                            }
                        }
                    }*/

                    $arr = [
                        'tableName'      => $data->tableName,
                        'tableAutoId'    => $data->tableAutoId,
                        'ram_detail_Id'  => $data->ram_detail_Id,
                        'strStatus'      => $data->strStatus,
                        'tableMainField' => $data->tableMainField,
                        'action'         => $data->action,
                        'isShow'         => $data->isShow,
                        'actionBy'       => $data->actionBy,
                        'strEntryDate'   => $data->strEntryDate,
                        'strRMARegistrationDate' => $data->strRMARegistrationDate,
                        'strMaterialDispatchedDate' => $data->strMaterialDispatchedDate,
                    ];
                    $datalog[] = (object)$arr; // Cast to object
                }

            return view('wladmin.Wl_RMA.rma-summary-info', compact('Documents_files','datalog', 'rmaList', 'rmadetailList', 'Documents',));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

     public function rma_month_summary_index(Request $request)
    {
        try {
            if (Auth::User()->role_id == 2) {
                $yearList = DB::table('yearlog')->orderBy('iYearId', 'DESC')->get();

                $status = $request->input('status');
                $fYear = $request->input('fYear');
                $datefrom = "";
                $dateto = "";
                if (isset($request->fYear) && $request->fYear != "") {
                    $yeardetail = DB::table('yearlog')->where('iYearId', $request->fYear)->first();
                    $datefrom = date('Y-m-d', strtotime($yeardetail->startDate));
                    $dateto = date('Y-m-d', strtotime($yeardetail->endDate));
                }
                
                $query = DB::table('rma')
                    ->selectRaw('MONTHNAME(strRMARegistrationDate) as month_name,
                                COUNT(*) as register_count,
                                SUM(CASE WHEN strStatus = "Open" THEN 1 ELSE 0 END) as open_count,
                                SUM(CASE WHEN strStatus = "Closed" THEN 1 ELSE 0 END) as closed_count');

                if (!empty($status)) {
                    $query->where('strStatus', $status);
                }
                if (isset($request->fYear) && $request->fYear != "") {
                    $query->whereBetween('rma.strRMARegistrationDate', [$datefrom, $dateto]);
                }
                $register_count = $query->groupByRaw('MONTHNAME(strRMARegistrationDate)')
                    ->where("rma.OemCompannyId","=", Session::get('CompanyId'))
                    ->orderByRaw('MONTH(strRMARegistrationDate)')
                    ->get();
                
                return view('wladmin.Wl_RMA.rma_month_summary', compact('fYear', 'status', 'yearList', 'register_count'));
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function rma_month_summary_info(Request $request, $id = null)
    {
        try {

            if (Auth::User()->role_id == 2) {
                $nmonth = date('m', strtotime($id));
                $year = date("Y");

                $open_rma_list = DB::table('rma')
                    ->where('strStatus', 'Open')
                    ->whereRaw("MONTH(STR_TO_DATE(strRMARegistrationDate, '%Y-%m-%d')) = ?", [$nmonth])
                    ->where("rma.OemCompannyId","=", Session::get('CompanyId'))
                    ->get();

                $closed_rma_list = DB::table('rma')
                    ->where('strStatus', 'Closed')
                    ->whereRaw("MONTH(STR_TO_DATE(strRMARegistrationDate, '%Y-%m-%d')) = ?", [$nmonth])
                    ->where("rma.OemCompannyId","=", Session::get('CompanyId'))
                    ->get();

                return view('wladmin.Wl_RMA.rma_month_summary_info', compact('id', 'open_rma_list', 'closed_rma_list', 'year'));
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function rma_month_call_info(Request $request, $id = null)
    {
        try {
            if (Auth::User()->role_id == 2) {

                $rmaList = Rma::select(
                    'rma.*',
                    'companydistributor.Name as distributor_name',
                    'users.first_name',
                    'users.last_name',
                    'ticketmaster.strTicketUniqueID',
                    'system.strSystem'
                )
                    ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, 'rma_id' => $id])
                    ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                    ->leftjoin('users', "rma.rma_enter_by", "=", "users.id")
                    ->leftjoin('ticketmaster', "rma.iComplaintId", "=", "ticketmaster.iTicketId")
                    ->leftjoin('system', 'rma.strSelectSystem', '=', 'system.iSystemId')
                    ->first();


                $rmadetailList = RmaDetail::select(
                    'rma_detail.*',
                    'system.iSystemId',
                    'system.strSystem as system_name',
                    'users.first_name',
                    'users.last_name'
                )
                    ->leftjoin('system', 'rma_detail.strSelectSystem', '=', 'system.iSystemId')
                    ->leftjoin('users', "rma_detail.rma_detail_enter_by", "=", "users.id")
                    ->where(["rma_detail.iStatus" => 1, 'rma_detail.isDelete' => 0, 'rma_id' => $id])
                    ->get();


                // $Documents = RmaDocs::orderBy('rma_docs_id', 'desc')
                //     ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                //     ->get();

                $Documents = RmaDocs::orderBy('rma_docs_id', 'desc')
                ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                ->where(function ($query) {
                    $query->whereNotNull('strImages')
                        ->orWhereNotNull('strVideos');
                })
                ->get();


            $Documents_files = RmaDocs::orderBy('rma_docs_id', 'desc')
                ->where(["iStatus" => 1, 'isDelete' => 0, 'rma_id' => $id, 'rma_detail_id' => 0])
                ->where(function ($query) {
                    $query->whereNotNull('strDocs')
                        ->orWhereNotNull('Factory_strDocs');
                })
                ->get();

                $Rma_info_log = DB::table('rma_infolog')
                ->select(
                'rma_infolog.*',
                DB::raw("(SELECT strRMARegistrationDate FROM rma WHERE rma.rma_id = rma_infolog.tableAutoId AND
                rma_infolog.tableName = 'rma') as strRMARegistrationDate"),
                DB::raw("(SELECT strMaterialDispatchedDate FROM rma WHERE rma.rma_id = rma_infolog.tableAutoId AND rma_infolog.tableName = 'rma') as strMaterialDispatchedDate")
                )
                ->where('tableName', 'rma')
                ->where('isShow', 1)
                ->where('tableAutoId', $id)
                ->union(
                DB::table('rma_infolog')
                ->select(
                'rma_infolog.*',
                DB::raw("(SELECT strRMARegistrationDate FROM rma_detail WHERE rma_detail.rma_detail_id = rma_infolog.ram_detail_Id AND
                rma_infolog.tableName = 'rma_detail') as strRMARegistrationDate"),
                 DB::raw("(SELECT strMaterialDispatchedDate FROM rma_detail WHERE rma_detail.rma_detail_id = rma_infolog.ram_detail_Id AND rma_infolog.tableName = 'rma_detail') as strMaterialDispatchedDate")
                )
                ->where('tableName', 'rma_detail')
                ->where('isShow', 1)
                ->where('tableAutoId', $id)
                )
                ->orderBy('id', 'ASC')
                ->get();

                $datalog = [];
                foreach ($Rma_info_log as $data) {
                    /*if ($data->ram_detail_Id > 0) {
                        $rma_detail_log = DB::table('rma_infolog')
                            ->where(['isShow' => 1, 'tableName' => 'rma_detail', 'ram_detail_Id' => $data->ram_detail_Id])
                            ->get();

                        if (count($rma_detail_log) >= 1) {
                            if ($data->strStatus != 'Open') {
                                $arr = [
                                    'tableName'      => $data->tableName,
                                    'tableAutoId'    => $data->tableAutoId,
                                    'ram_detail_Id'  => $data->ram_detail_Id,
                                    'strStatus'      => 'Open',
                                    'tableMainField' => $data->tableMainField,
                                    'action'         => $data->action,
                                    'isShow'         => $data->isShow,
                                    'actionBy'       => $data->actionBy,
                                    'strEntryDate'   => $data->strEntryDate,
                                ];
                                $datalog[] = (object)$arr; // Cast to object
                            }
                        }
                    }*/

                    $arr = [
                        'tableName'      => $data->tableName,
                        'tableAutoId'    => $data->tableAutoId,
                        'ram_detail_Id'  => $data->ram_detail_Id,
                        'strStatus'      => $data->strStatus,
                        'tableMainField' => $data->tableMainField,
                        'action'         => $data->action,
                        'isShow'         => $data->isShow,
                        'actionBy'       => $data->actionBy,
                        'strEntryDate'   => $data->strEntryDate,
                        'strRMARegistrationDate' => $data->strRMARegistrationDate,
                        'strMaterialDispatchedDate' => $data->strMaterialDispatchedDate,
                    ];
                    $datalog[] = (object)$arr; // Cast to object
                }

                return view('wladmin.Wl_RMA.rma_month_call_info', compact('Documents_files','datalog', 'rmaList', 'rmadetailList', 'Documents'));
            } else {
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function openDocument(Request $request, $id){
        $root = $_SERVER['DOCUMENT_ROOT'];
        $RmaDocs = RmaDocs::where(["rma_docs_id" => $id])->first();
        $destinationPath = "";
        if($RmaDocs->strDocs != ""){
            $destinationPath =  asset('RMADOC/docs/') . '/' . $RmaDocs->strDocs;
        } else if($RmaDocs->Factory_strDocs != ""){
            $destinationPath =  asset('RMADOC/docs/') . '/' . $RmaDocs->Factory_strDocs;
        }
        $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);
            if ($ext == 'pdf')
                echo   "<iframe src='" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
            else
                echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        //echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";

    }

    public function rmadetail_edit(Request $request,$id=null)
    {

         $rma_detail = DB::table('rma_detail')->where('rma_detail_id', $request->rma_detail_id)->first();

         $date_fields = [
            'strRMARegistrationDate',
            'strMaterialReceivedDate',
            'strTestingCompleteDate',
            'strFactory_MaterialReceivedDate',
            'strFactory_TestingCompleteDate',
            'strMaterialDispatchedDate'
        ];

        foreach ($date_fields as $field)
        {
            if (!is_null($rma_detail->$field)) {

                $rma_detail->$field = \Carbon\Carbon::parse($rma_detail->$field)->format('d-m-Y');
            } else {

                $rma_detail->$field = null;
            }
        }

       return response()->json($rma_detail);
    }
    public function get_Complaint_id(Request $request)
    {
        $companyid = DB::table('companymaster')->where('iCompanyId', $request->iCompanyId)->first();
        $ticketLists = TicketMaster::where(["iStatus" => 1, 'isDelete' => 0, 'finalStatus' => 4])
            ->orderBy('iTicketId', 'asc')
            ->when($request->iCompanyId, fn($query, $OemCompannyId) => $query->where('ticketmaster.OemCompannyId', $OemCompannyId));
        $ticketLists->whereNotIn('ticketmaster.iTicketId', function ($query) {
            $query->select('iComplaintId')->from('rma');
        });
        $ticketList = $ticketLists->get();

        $maxId = DB::table('rma')
            ->where('OemCompannyId', $request->iCompanyId)
            ->max('iOEM_RMA_Id');
        $iRMANumber = "";
        if (isset($companyid->strCompanyPrefix) && $companyid->strCompanyPrefix != "") {
            $iOEM_RMA_Id = $maxId ?? 0;
            $iOEM_RMA_Id = $iOEM_RMA_Id + 1;
            $iRMANumber = $companyid->strCompanyPrefix . "-RN" . str_pad($iOEM_RMA_Id, 4, '0', STR_PAD_LEFT);
        }

        $ticketListhtml = '<option label="Please Select" value="">-- Select --</option>';
        foreach ($ticketList as $system) {
            $ticketListhtml .= '<option value="' . $system->iTicketId . '">' . $system->strTicketUniqueID . '</option>';
        }

        $ticketListhtml .= ' <option value="0">Other</option>';

        return response()->json([
            $ticketListhtml,
            $iRMANumber
        ]);
    }

    public function reorder_column(Request $request)
    {
        $userid = Auth::user()->id;
        $referer = $request->server('HTTP_REFERER');
        $route = Route::getRoutes()->match(app('request')->create($referer));
        $routeName = $route->getName();
        $checkboxesJson = json_encode($request->checkboxes);

        $menudata =  DB::table('reorder_columns')->where(['strUrl' => $routeName, 'iUserId' => $userid])->first();

        if (!$menudata) {
            $data = array(
                'strUrl' => $routeName,
                'json' => $checkboxesJson,
                'iUserId' => $userid,
            );
            DB::table('reorder_columns')->insert($data);
        } else {
            $data = array(
                'json' => $checkboxesJson
            );
            DB::table('reorder_columns')->where(['strUrl' => $routeName, 'iUserId' => $userid])->update($data);
        }

        return response()->json(['message' => 'Checkboxes saved successfully!']);
    }
    public function reorder_column_rma_detail(Request $request)
    {
        $userid = Auth::user()->id;
        $referer = $request->server('HTTP_REFERER');
        $route = Route::getRoutes()->match(app('request')->create($referer));
        $routeName = $route->getName();
        $checkboxesJson = json_encode($request->checkboxes);

        $menudata =  DB::table('reorder_columns')->where(['strUrl' => $routeName, 'iUserId' => $userid])->first();

        if (!$menudata) {
            $data = array(
                'strUrl' => $routeName,
                'json' => $checkboxesJson,
                'iUserId' => $userid,
            );
            DB::table('reorder_columns')->insert($data);
        } else {
            $data = array(
                'json' => $checkboxesJson
            );
            DB::table('reorder_columns')->where(['strUrl' => $routeName, 'iUserId' => $userid])->update($data);
        }

        return response()->json(['message' => 'Checkboxes saved successfully!']);
    }
}
