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


class RMAController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 3) {

            $session = Session::get('CompanyId');
            $userID = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, "iUserId" => Auth::user()->id])
                ->first();

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
            $ticketList = $ticketLists->get();

            $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
                ->orderBy('strSystem', 'asc')
                ->get();

            $distributorList = Distributor::where(["iStatus" => 1, 'isDelete' => 0])
                ->orderBy('Name', 'asc')
                ->get();

            $rmaCount = Rma::where(["iStatus" => 1, 'isDelete' => 0])->count();
            $iRMANumber = 'RN' . str_pad($rmaCount + 1, 4, '0', STR_PAD_LEFT); // Generate dynamic RMA number

            $rmaList = Rma::select(
                'rma.*',
                'companydistributor.Name as distributor_name'
            )
                ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0])
                ->join('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
                ->orderBy('iRMANumber', 'asc')
                ->paginate(10);

            // dd($distributorList);

            return view('call_attendant.rma.add', compact('ticketList', 'systemList', 'distributorList', 'iRMANumber', 'rmaList'));
        } else {
            return redirect()->route('home');
        }
    }
    public function get_details(Request $request)
    {
        // dd($request);
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
    }
    public function store(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');


            $array = array(
                "iComplaintId" => $request->iComplaintId ?? 0,
                "iRMANumber" => $request->iRMANumber ?? 0,
                "strCustomerCompany" => $request->strCustomerCompany,
                "strCustomerEngineer" => $request->strCustomerEngineer,
                "strDistributor" => $request->strDistributor,
                "strProjectName" => $request->strProjectName,
                "strRMARegistrationDate" => date('Y-m-d', strtotime($request->strRMARegistrationDate)),
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
                "strMaterialReceivedDate" => date('Y-m-d', strtotime($request->strMaterialReceivedDate)),
                "strTesting" => $request->strTesting,
                "strTestingCompleteDate" => date('Y-m-d', strtotime($request->strTestingCompleteDate)),
                "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                "strReplacementApproved" => $request->strReplacementApproved,
                "strReplacementReason" => $request->strReplacementReason,
                "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                "strFactory_MaterialReceivedDate" => date('Y-m-d', strtotime($request->strFactory_MaterialReceivedDate)),
                "strFactory_Testing" => $request->strFactory_Testing,
                "strFactory_TestingCompleteDate" => date('Y-m-d', strtotime($request->strFactory_TestingCompleteDate)),
                "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                "strMaterialDispatched" => $request->strMaterialDispatched,
                "strMaterialDispatchedDate" => date('Y-m-d', strtotime($request->strMaterialDispatchedDate)),
                "strStatus" => $request->strStatus,
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


            return redirect()->route('rma.index')->with('Success', 'RMA Created Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function additional_rma(Request $request, $id)
    {
        $session = Session::get('CompanyId');

        $rmaList = Rma::select(
            'rma.*',
            'companydistributor.Name as distributor_name'
        )
            ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, 'rma_id' => $id])
            ->join('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
            ->orderBy('iRMANumber', 'asc')
            ->first();
        // dd($rmaList);

        $ticketList = TicketMaster::where(["iStatus" => 1, 'isDelete' => 0])
            ->orderBy('iTicketId', 'asc')
            ->get();

        $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
            ->orderBy('strSystem', 'asc')
            ->get();

        $distributorList = Distributor::where(["iStatus" => 1, 'isDelete' => 0])
            ->orderBy('Name', 'asc')
            ->get();


        return view('call_attendant.rma.additional_rma', compact('rmaList', 'ticketList', 'systemList', 'distributorList'));
    }
    public function additional_rma_store(Request $request)
    {

        if (Auth::User()->role_id == 3) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            $array = array(
                "rma_id" => $request->rma_id ?? 0,
                "iRMANumber" => $request->iRMANumber ?? 0,
                "strRMARegistrationDate" => \Carbon\Carbon::parse($request->strRMARegistrationDate)->format('Y-m-d'),
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
                "strMaterialReceivedDate" => \Carbon\Carbon::parse($request->strMaterialReceivedDate)->format('Y-m-d'),
                "strTesting" => $request->strTesting,
                "strTestingCompleteDate" => \Carbon\Carbon::parse($request->strTestingCompleteDate)->format('Y-m-d'),
                "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                "strReplacementApproved" => $request->strReplacementApproved,
                "strReplacementReason" => $request->strReplacementReason,
                "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                "strFactory_MaterialReceivedDate" => \Carbon\Carbon::parse($request->strFactory_MaterialReceivedDate)->format('Y-m-d'),
                "strFactory_Testing" => $request->strFactory_Testing,
                "strFactory_TestingCompleteDate" => \Carbon\Carbon::parse($request->strFactory_TestingCompleteDate)->format('Y-m-d'),
                "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                "strMaterialDispatched" => $request->strMaterialDispatched,
                "strMaterialDispatchedDate" =>  \Carbon\Carbon::parse($request->strMaterialDispatchedDate)->format('Y-m-d'),
                "strStatus" => $request->strStatus,
                "created_at" => now(),
                "strIP" => $request->ip(),
                "rma_detail_enter_by" => Auth::User()->id,
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


            return redirect()->route('rma.index')->with('Success', 'RMA Detail Created Successfully.');
        } else {
            return redirect()->route('home');
        }
    }
    public function rma_info(Request $request, $id)
    {

        $session = Session::get('CompanyId');
        $systemList = System::where(["iStatus" => 1, 'isDelete' => 0, 'iCompanyId' => $session])
            ->orderBy('strSystem', 'asc')
            ->get();

        $rmaList = Rma::select(
            'rma.*',
            'companydistributor.Name as distributor_name',
            'users.first_name',
            'users.last_name'
        )
            ->where(["rma.iStatus" => 1, 'rma.isDelete' => 0, 'rma_id' => $id])
            ->leftjoin('companydistributor', "companydistributor.iDistributorId", "=", "rma.strDistributor")
            ->leftjoin('users', "rma.rma_enter_by", "=", "users.id")
            ->leftjoin('ticketmaster', "rma.iComplaintId", "=", "ticketmaster.iTicketId")
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
            ->get();


        return view('call_attendant.rma.info', compact('rmaList', 'Documents', 'rmadetailList', 'id', 'systemList'));
    }
    public function rma_edit(Request $request)
    {
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
            $cus_Status .= '<option value="Open">Open</option>';
        }
        if ($response->strStatus == 'Closed') {
            $cus_Status .= '<option value="Closed" selected>Closed</option>';
        } else {
            $cus_Status .= '<option value="Closed">Closed</option>';
        }
        //--------------------------field 13 end ----------------------------

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
                $cus_Status
            ]);
        }
        return response()->json([
            'error' => 'RMA record not found'
        ], 404);
    }
    public function rma_update(Request $request)
    {
        try {
            if (Auth::User()->role_id == 3) {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $array = array(
                    "strRMARegistrationDate" => \Carbon\Carbon::parse($request->strRMARegistrationDate)->format('Y-m-d'),
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
                    "strMaterialReceivedDate" => \Carbon\Carbon::parse($request->Material_Received_Date)->format('Y-m-d'),
                    "strTesting" => $request->strTesting,
                    "strTestingCompleteDate" => \Carbon\Carbon::parse($request->strTestingCompleteDate)->format('Y-m-d'),
                    "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                    "strReplacementApproved" => $request->strReplacementApproved,
                    "strReplacementReason" => $request->strReplacementReason,
                    "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                    "strFactory_MaterialReceivedDate" => \Carbon\Carbon::parse($request->Factory_Material_Received_Date)->format('Y-m-d'),
                    "strFactory_Testing" => $request->strFactory_Testing,
                    "strFactory_TestingCompleteDate" => $request->strFactory_TestingCompleteDate,
                    "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                    "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                    "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                    "strMaterialDispatched" => $request->strMaterialDispatched,
                    "strMaterialDispatchedDate" => \Carbon\Carbon::parse($request->strMaterialDispatchedDate)->format('Y-m-d'),
                    "strStatus" => $request->strStatus,
                    "updated_at" => now(),
                    "strIP" => $request->ip(),
                    "rma_enter_by" => Auth::User()->id,
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
                $userdata = \App\Models\User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "rma",
                    'tableAutoId'    => $request->rma_id,
                    'tableMainField'  => "RMA detail update",
                    'action'     => "updated",
                    'strEntryDate' => now(),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
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
                    "strRMARegistrationDate" => \Carbon\Carbon::parse($request->strRMARegistrationDate)->format('Y-m-d'),
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
                    "strMaterialReceivedDate" => $request->Material_Received_Date,
                    "strTesting" => $request->strTesting,
                    "strTestingCompleteDate" => \Carbon\Carbon::parse($request->strTestingCompleteDate)->format('Y-m-d'),
                    "strFaultCoveredinWarranty" => $request->strFaultCoveredinWarranty,
                    "strReplacementApproved" => $request->strReplacementApproved,
                    "strReplacementReason" => $request->strReplacementReason,
                    "strFactory_MaterialReceived" => $request->strFactory_MaterialReceived,
                    "strFactory_MaterialReceivedDate" => \Carbon\Carbon::parse($request->Factory_Material_Received_Date)->format('Y-m-d'),
                    "strFactory_Testing" => $request->strFactory_Testing,
                    "strFactory_TestingCompleteDate" => \Carbon\Carbon::parse($request->strFactory_TestingCompleteDate)->format('Y-m-d'),
                    "strFactory_FaultCoveredinWarranty" => $request->strFactory_FaultCoveredinWarranty,
                    "strFactory_ReplacementApproved" => $request->strFactory_ReplacementApproved,
                    "strFactory_ReplacementReason" => $request->strFactory_ReplacementReason,
                    "strMaterialDispatched" => $request->strMaterialDispatched,
                    "strMaterialDispatchedDate" =>  \Carbon\Carbon::parse($request->strMaterialDispatchedDate)->format('Y-m-d'),
                    "strStatus" => $request->strStatus,
                    "created_at" => now(),
                    "strIP" => $request->ip(),
                    "rma_detail_enter_by" => Auth::User()->id,
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
}
