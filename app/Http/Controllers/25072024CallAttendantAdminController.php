<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\TicketMaster;
use App\Models\infoTable;
use App\Models\CallAttendent;
use File;
use Image;
use Illuminate\Support\Facades\Mail;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\Auth;



class CallAttendantAdminController extends Controller
{

    public function homeindex(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            return view('call_attendant.home');
        } else {
            return redirect()->route('home');
        }
    }
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();


            return view('call_attendant.index', compact('CompanyMaster', 'statemasters', 'citymasters'));
        } else {
            return redirect()->route('home');
        }
    }
    public function getCompanyDetail(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            return view('call_attendant.index');
        } else {
            return redirect()->route('home');
        }
    }
    public function checkCustNumber(Request $request)
    {
        $custNumber = $request->custNumber;
        $ticketList = TicketMaster::where("ticketmaster.CustomerMobile",  "$custNumber")
            ->select('iTicketId', 'iTicketStatus', 'ticketName', 'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName')
            ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus")
            ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
            ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
            ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
            ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
            ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
            ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
            ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
            ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
            ->orderBy('iTicketId', 'DESC')
            ->get();

        if (count($ticketList) > 0) {
            $ticketHtml = '';
            $iCounter = 1;
            foreach ($ticketList as $ticket) {
                $ticketHtml .= '<tr>
                <td>' . $iCounter . '</td>
                <td>' . str_pad($ticket->iTicketId, 4, "0", STR_PAD_LEFT) . '</td>
                <td>' . $ticket->ticketName . '</td>
                <td>' . $ticket->CustomerName . '</td>
                <td>' . $ticket->CustomerMobile . '</td>
                <td>' . $ticket->strOEMCompanyName . '</td>
                <td>' . $ticket->CompanyName . '</td>
                <td>' . $ticket->strSystem . '</td>
                <td>' . $ticket->strComponent . '</td>
                <td>' . $ticket->strSubComponent . '</td>
                <td>' . $ticket->strResolutionCategory . '</td>
                <td>' . date('d-m-Y', strtotime($ticket->ComplainDate)) . '<br>
                    <small>' . date('H:i:s', strtotime($ticket->ComplainDate)) . '</small>
                </td>';
                if ($ticket->ResolutionDate != NULL && in_array($ticket->finalStatus, array(1, 2, 4))) {
                    $ticketHtml .= '<td>' . date('d-m-Y', strtotime($ticket->ResolutionDate)) . '<br>
                <small>' . date('H:i:s', strtotime($ticket->ResolutionDate)) . '</small>

                </td>';
                } else {
                    $ticketHtml .= '<td>-</td>';
                }
                $ticketHtml .= '<td>' . $ticket->strIssueType . '</td>
                <td>' . $ticket->first_name . ' ' . $ticket->last_name . '</td>
                <td>
                    <a href="../complaint/info/' . $ticket->iTicketId . '" title="Info" class="table-action">
                        <i class="mas-info-circle"></i>
                    </a>';
                if (in_array($ticket->finalStatus, array(1, 2, 4)))
                    $ticketHtml .= '<a href="../complaint/edit/' . $ticket->iTicketId . '" title="Reopen" class="table-action">
                        <i class="mas-reopen"></i></a>';
                else
                    $ticketHtml .= '<a href="../complaint/edit/' . $ticket->iTicketId . '" title="Edit" class="table-action">
                    <i class="mas-edit"></i></a>';
                $ticketHtml .= '</td></tr>';
                $iCounter++;
            }
            echo $ticketHtml;
        } else {
            echo '0';
        }
    }

    public function autofillCustNumberListComplain(Request $request)
    {
        $customerNumber = $request->customerNumber;
        $customerName = $request->customerName;
        $ticketList = TicketMaster::where("ticketmaster.CustomerMobile",  "$customerNumber")
            ->where("ticketmaster.CustomerName", 'like',  "%" . $customerName . "%")
            ->select('iTicketId', 'iTicketStatus', 'ticketName', 'CustomerMobile', 'CustomerEmail', 'OemCompannyId', 'ticketmaster.iCompanyId', 'iCompanyProfileId', 'CustomerEmailCompany', 'OtherInformation', 'iDistributorId', 'ProjectName', 'ticketmaster.iStateId', 'ticketmaster.iCityId', 'iCallThrough', 'UserDefiine1', 'ticketmaster.iSystemId', 'ticketmaster.iComnentId', 'ticketmaster.iSubComponentId', 'ticketmaster.iSupportType', 'issue', 'Resolutiondetail', 'ticketmaster.iResolutionCategoryId', 'ticketmaster.iIssueTypeId', 'ticketmaster.CallerCompetencyId', 'ticketmaster.iTicketStatus', 'finalStatus', 'ticketmaster.LevelId', 'comments', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName')
            ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus")
            ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
            ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
            ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
            ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
            ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
            ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
            ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
            ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
            ->orderBy('iTicketId', 'DESC')
            ->first();
        //dd($ticketList);
        echo $ticketList;
    }

    public function create(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $iStatus = 1;
            $dataInfo = array();
            if ($request->iCompanyProfileId == 'Other' && $request->othrcompanyprofile != '') {
                $SendEmailDetails = DB::table('sendemaildetails')
                    ->where(['id' => 10])
                    ->first();
                $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1, 'iCompanyId' => $request->OemCompannyId])
                    ->first();
                $company_Info = CompanyInfo::where('iCompanyId', '=', $request->OemCompannyId)->first();

                if (!empty($company_Info)) {
                    $dataInfo = array(
                        "CompanyName" => $company_Info->strCompanyName,
                        "CompanyEmail" => $company_Info->EmailId,
                        "instaLink" => $company_Info->instaLink,
                        "twitterLink" => $company_Info->twitterlink,
                        "facebookLink" => $company_Info->facebookLink,
                        "linkedinLink" => $company_Info->linkedinlink,
                        "Logo" => $company_Info->strLogo
                    );
                } else {
                    $company_Info = CompanyInfo::where('iCompanyInfoId', '=', 1)->first();
                    $dataInfo = array(
                        "CompanyName" => $company_Info->strCompanyName,
                        "CompanyEmail" => $company_Info->EmailId,
                        "instaLink" => $company_Info->instaLink,
                        "twitterLink" => $company_Info->twitterlink,
                        "facebookLink" => $company_Info->facebookLink,
                        "linkedinLink" => $company_Info->linkedinlink,
                        "Logo" => $company_Info->strLogo
                    );
                }

                $msg = array(
                    'FromMail' => $SendEmailDetails->strFromMail,
                    'Title' => $SendEmailDetails->strTitle,
                    'ToEmail' => $CompanyMaster['EmailId'],
                    'Subject' => "Add new company profile request"
                );

                $mail = Mail::send('emails.alertMail', ['Company' => $request->othrcompanyprofile, "data" => $dataInfo], function ($message) use ($msg) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                });
                $iCompanyProfileId = -1;
            } else {
                $iCompanyProfileId = $request->iCompanyProfileId ? $request->iCompanyProfileId : 0;
            }

            if ($request->iCompanyId == 'Other' && $request->othrcompanyname != '') {
                $companyData = array(
                    "CompanyName" => $request->othrcompanyname,
                    "email" => $request->CustomerEmailCompany,
                    "iCompanyId" => $request->OemCompannyId,
                    "iEntryBy" => $session
                );
                $companyId = DB::table('companyclient')->insertGetId($companyData);
            } else {
                $companyId = $request->iCompanyId ? $request->iCompanyId : 0;
            }
            $level2Exe = 0;
            if ($request->LevelId) {
                if ($request->LevelId == '2' && $request->iLevel2CallAttendentId != '') {
                    $level2Exe = $request->iLevel2CallAttendentId;
                }
            }

            $levelId = 1;

            if ($request->LevelId) {
                if (Session::get('exeLevel') == 2)
                    $levelId = 2;
                else
                    $levelId = $request->LevelId;
            } else {
                if (Session::get('exeLevel') == 2)
                    $levelId = 2;
            }

            $iSubComponentId = "";
            if(isset($request->iSubComponentId)){
                foreach ($request->iSubComponentId as $SubComponentId) {
                    $iSubComponentId .= $SubComponentId . ",";
                }
            }
            $iSubComponentId = rtrim($iSubComponentId, ",");
            //dd($iSubComponentId);
            $ticket = array(
                "iCustomerComplainUserId" => 0,
                "CustomerMobile" => $request->customerNumber,
                "CustomerName" => $request->customerName,
                "CustomerEmail" => $request->customerEmail,
                "OemCompannyId" => $request->OemCompannyId ? $request->OemCompannyId : 0,
                "iCompanyId" => $companyId,
                "iCompanyProfileId" => $iCompanyProfileId,
                "otherCompanyProfile" => $request->othrcompanyprofile ? $request->othrcompanyprofile : '',
                "CustomerEmailCompany" => $request->CustomerEmailCompany,
                "OtherInformation" => $request->OtherInformation,
                "iDistributorId" => $request->iDistributorId ? $request->iDistributorId : 0,
                "ProjectName" => $request->ProjectName,
                "iStateId" => $request->iStateId ? $request->iStateId : 0,
                "iCityId" => $request->iCityId ? $request->iCityId : 0,
                "iCallThrough" => $request->iCallThrough,
                "UserDefiine1" => $request->UserDefiine1,
                "iSystemId" => $request->iSystemId ? $request->iSystemId : 0,
                "iComnentId" => $request->iComponentId ? $request->iComponentId : 0,
                //"iSubComponentId" => $request->iSubComponentId ? $request->iSubComponentId : 0,
                "iSubComponentId" => $iSubComponentId ?? 0,
                "iSupportType" => $request->iSupportType ? $request->iSupportType : 0,
                "issue" => $request->issue,
                "Resolutiondetail" => $request->Resolutiondetail,
                "iResolutionCategoryId" => $request->iResolutionCategoryId ? $request->iResolutionCategoryId : 0,
                "iIssueTypeId" => $request->iIssueTypeId ? $request->iIssueTypeId : 0,
                "CallerCompetencyId" => $request->CallerCompetencyId ? $request->CallerCompetencyId : 0,
                "iTicketStatus" => $request->iTicketStatus,
                "finalStatus" => $request->iTicketStatus,
                "LevelId" => $levelId,
                "iCallAttendentId" => $session,
                "oldStatus" => $request->oldStaus,
                "oldStatusDatetime" => $request->oldStausDatetime,
                "iLevel2CallAttendentId" => $level2Exe,
                "comments" => $request->comments,
                "iStatus" => 1,
                "isDelete" => 0,
                "strIP" => $request->ip()

            );

            if ($request->iTicketStatus == 1 || $request->iTicketStatus == 4 || $request->iTicketStatus == 5) {
                $ticket["ResolutionDate"] = date('Y-m-d H:i:s');
            }

            $iTicketId = DB::table('ticketmaster')->insertGetId($ticket);


            if ($request->hasfile('tcktImages')) {

                foreach ($request->file('tcktImages') as $file) {
                    $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                    $name = time() . rand(1, 50) . '.' . $file->extension();
                    $destinationpath = $root . "ticket_images/";
                    if (!file_exists($destinationpath)) {
                        mkdir($destinationpath, 0755, true);
                    }
                    if ($file->move($destinationpath, $name)) {
                        $Data = array(
                            "iTicketId" =>  $iTicketId,
                            "DocumentType" => 2,
                            "DocumentName" => $name,
                            "iStatus" => 1,
                            "isDelete" => 0,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            "strIP" => $request->ip(),
                        );

                        $ticketdetailId = DB::table('ticketdetail')->insertGetId($Data);
                    } else {
                        $iStatus = 0;
                    }
                }
            }

            if ($request->hasfile('tcktVideo')) {

                foreach ($request->file('tcktVideo') as $file) {
                    $root = $_SERVER['DOCUMENT_ROOT'] . "/";
                    $name = time() . rand(1, 50) . '.' . $file->extension();
                    $destinationpath = $root . "ticket_video/";
                    if (!file_exists($destinationpath)) {
                        mkdir($destinationpath, 0755, true);
                    }
                    if ($file->move($destinationpath, $name)) {

                        $Data = array(
                            "iTicketId" =>  $iTicketId,
                            "DocumentType" => 3,
                            "DocumentName" => $name,
                            "iStatus" => 1,
                            "isDelete" => 0,
                            "strEntryDate" => date('Y-m-d H:i:s'),
                            "strIP" => $request->ip(),
                        );

                        $ticketdetailId = DB::table('ticketdetail')->insertGetId($Data);
                    } else {
                        $iStatus = 0;
                    }
                }
            }

            $userdata = \App\Models\User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "ticketmaster",
                'tableAutoId'    => $iTicketId,
                'tableMainField'  => "Ticket entered",
                'action'     => "Inserted",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );

            if ($levelId == 2 && $level2Exe != 0) {
                $SendEmailDetails = DB::table('sendemaildetails')
                    ->where(['id' => 6])
                    ->first();
                $companyInfo = CompanyInfo::where('iCompanyId', '=', $request->OemCompannyId)->first();
                if (!empty($companyInfo)) {
                    $data = array(
                        "CompanyEmail" => $companyInfo->EmailId,
                        "instaLink" => $companyInfo->instaLink,
                        "twitterLink" => $companyInfo->twitterlink,
                        "facebookLink" => $companyInfo->facebookLink,
                        "linkedinLink" => $companyInfo->linkedinlink,
                        "Logo" => $companyInfo->strLogo
                    );
                } else {
                    $companyInfo = CompanyInfo::where('iCompanyInfoId', '=', 1)->first();
                    $data = array(
                        "CompanyEmail" => $companyInfo->EmailId,
                        "instaLink" => $companyInfo->instaLink,
                        "twitterLink" => $companyInfo->twitterlink,
                        "facebookLink" => $companyInfo->facebookLink,
                        "linkedinLink" => $companyInfo->linkedinlink,
                        "Logo" => $companyInfo->strLogo
                    );
                }
                $exeDetail = DB::table('callattendent')->where('iCallAttendentId', $level2Exe)
                    ->first();
                $msg = array(
                    'FromMail' => $SendEmailDetails->strFromMail,
                    'Title' => $SendEmailDetails->strTitle,
                    'ToEmail' =>   $exeDetail->strEmailId,
                    'Subject' => "Complaint Update"
                );

                $mail = Mail::send('emails.alertL2Mail', ['Name' => $exeDetail->strFirstName . ' ' . $exeDetail->strLastName, "data" => $data], function ($message) use ($msg) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                });
            }

            $Info = infoTable::create($infoArr);
            $currentTime = time();
            $beginning_of_day = strtotime("midnight", $currentTime);
            $chkCall = DB::table('ticketcall')
                ->where(['callerNumber' => $request->customerNumber, "isMaped" => 0])
                ->where('call_state', 'answered')
                ->whereBetween('entryDatetime', [$beginning_of_day, $currentTime])
                ->orderby('iTicketCallId', 'DESC')
                ->first();
            if ($chkCall) {

                $client = new \GuzzleHttp\Client();

                $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/call/records?call_id=' . $chkCall->callId, [
                    'headers' => [
                        'Authorization' => config('site_vars.tata_token'),
                        'accept' => 'application/json'
                    ],
                ]);

                $responseAPI = json_decode($response->getBody(), true);


                if ($responseAPI['count'] == 1) {
                    $apiResult = $responseAPI['results'][0];
                    if (isset($apiResult['status'])) {
                        if ($apiResult['status'] == 'answered' && $chkCall->callId == $apiResult['call_id']) {
                            $updateData = array(
                                "tataCallId" => $chkCall->callId,
                                "recordUrl" => $apiResult['recording_url']
                            );

                            DB::table('ticketmaster')->where("iTicketId", '=', $iTicketId)
                                ->where("tataCallId", '=', null)
                                ->update($updateData);
                            DB::table('ticketcall')->where("iTicketCallId", '=', $chkCall->iTicketCallId)
                                ->where(['callerNumber' => $request->customerNumber, "isMaped" => 0, "call_state" => 'answered'])
                                ->update(["isMaped" => 1, "iTicketId" => $iTicketId]);
                        }
                    }
                }
            }


            /***************Mail Code********************/
            $SendEmailDetails = DB::table('sendemaildetails')
                ->where(['id' => 9])
                ->first();
            $companyInfo = CompanyInfo::where('iCompanyId', '=', $request->OemCompannyId)->first();

            $iStatusId = 0;
            if ($request->iTicketStatus == 0 || $request->iTicketStatus == 3) {
                $iStatusId = 0;
            } else {
                $iStatusId = 1;
            }
            $messagemaster = DB::table('messagemaster')
                ->where(['iCompanyId' => $request->OemCompannyId, "iStatusId" => $iStatusId])
                ->first();

            $ticketstatus = DB::table('ticketstatus')->where('istatusId', $request->iTicketStatus)->first();

            if (!empty($companyInfo)) {
                $data = array(
                    "CompanyName" => $companyInfo->strCompanyName,
                    "Message" => $messagemaster->strMessage ?? "",
                    "TicketID" => str_pad($iTicketId, 4, "0", STR_PAD_LEFT),
                    "TicketStatus" => $ticketstatus->ticketName,
                    "CustomerMobile" => $request->customerNumber,
                    "CustomerName" => $request->customerName,
                    "TicketOpenDate" => date('d/m/Y, H:i:s'),
                    "CompanyEmail" => $companyInfo->EmailId,
                    "CompanyMobile" => $companyInfo->ContactNo,
                    "instaLink" => $companyInfo->instaLink,
                    "twitterLink" => $companyInfo->twitterlink,
                    "facebookLink" => $companyInfo->facebookLink,
                    "linkedinLink" => $companyInfo->linkedinlink,
                    "Logo" => $companyInfo->strLogo
                );
            } else {
                $companyInfo = CompanyInfo::where('iCompanyInfoId', '=', 1)->first();
                $data = array(
                    "CompanyName" => $companyInfo->strCompanyName,
                    "Message" => $messagemaster->strMessage ?? "",
                    "TicketID" => str_pad($iTicketId, 4, "0", STR_PAD_LEFT),
                    "TicketStatus" => $ticketstatus->ticketName,
                    "CustomerMobile" => $request->customerNumber,
                    "CustomerName" => $request->customerName,
                    "TicketOpenDate" => date('d/m/Y, H:i:s'),
                    "CompanyEmail" => $companyInfo->EmailId,
                    "CompanyMobile" => $companyInfo->ContactNo,
                    "instaLink" => $companyInfo->instaLink,
                    "twitterLink" => $companyInfo->twitterlink,
                    "facebookLink" => $companyInfo->facebookLink,
                    "linkedinLink" => $companyInfo->linkedinlink,
                    "Logo" => $companyInfo->strLogo
                );
            }
            $ToEmail = array();
            if (!empty($messagemaster)) {
                if ($messagemaster->toCustomer != 0) {
                    if ($request->customerEmail != "") {
                        $ToEmail[] = $request->customerEmail;
                    }
                }
                if ($messagemaster->toCompany != 0) {
                    //$CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1,'iCompanyId' => $request->OemCompannyId])->first();
                    if ($request->CustomerEmailCompany != "") {
                        $ToEmail[] = $request->CustomerEmailCompany;
                    }
                }
                if ($messagemaster->toExecutive != 0) {
                    if ($request->LevelId == 2) {
                        if ($request->iLevel2CallAttendentId != "") {
                            $CallAttendent = CallAttendent::where(['isDelete' => 0, 'iStatus' => 1, 'iCallAttendentId' => $request->iLevel2CallAttendentId])->first();
                            $ToEmail[] = $CallAttendent->strEmailId;
                        }
                    }
                }
            }
            if (count($ToEmail) > 0) {
                $msg = array(
                    'FromMail' => $SendEmailDetails->strFromMail,
                    'Title' => $SendEmailDetails->strTitle,
                    'ToEmail' => $ToEmail,
                    'Subject' => $SendEmailDetails->strSubject
                );

                $mail = Mail::send('emails.ticketupdate', ['data' => $data], function ($message) use ($msg) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                });
            }
            if ($iStatus == 1) {
                Session::flash('Success', 'Ticket Added Succefully!');
            } else {
                Session::flash('Error', 'Error in update data!');
            }
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->get();

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();

            return redirect()->route('complaint.index', compact('CompanyMaster', 'statemasters', 'citymasters'))->with('Success', 'ticket Created Successfully.');
        } else {
            return redirect()->route('home');
        }
    }
    function checkCallResponse(Request $request)
    {
        DB::enableQueryLog();
        $currentTime = date('Y-m-d H:i:s');
        $beginning_of_day = date('Y-m-d 00:00:00', strtotime($currentTime));
        $chkcomplain = DB::table('ticketmaster')->where('CustomerMobile', '7259755329')
            ->wherebetween('strEntryDate', [$beginning_of_day, $currentTime])
            ->where('tataCallId', null)
            ->where('recordUrl', null)
            ->get();
        dd($chkcomplain);
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', 'https://api-smartflo.tatateleservices.com/v1/call/records?call_id=1694593195.290357', [
        //     'headers' => [
        //         'Authorization' => config('site_vars.tata_token'),
        //         'accept' => 'application/json'
        //     ],
        // ]);

        // $responseAPI = json_decode($response->getBody());
        // if ($responseAPI) {
        //     //  dd($responseAPI->results);
        // }
    }

    public function update(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $iStatus = 1;
            $dataInfo = array();
            if ($request->iCompanyProfileId == 'Other' && $request->othrcompanyprofile != '') {
                $SendEmailDetails = DB::table('sendemaildetails')
                    ->where(['id' => 10])
                    ->first();
                $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1, 'iCompanyId' => $request->OemCompannyId])
                    ->first();
                $company_Info = CompanyInfo::where('iCompanyId', '=', $request->OemCompannyId)->first();

                if (!empty($company_Info)) {
                    $dataInfo = array(
                        "CompanyName" => $company_Info->strCompanyName,
                        "CompanyEmail" => $company_Info->EmailId,
                        "instaLink" => $company_Info->instaLink,
                        "twitterLink" => $company_Info->twitterlink,
                        "facebookLink" => $company_Info->facebookLink,
                        "linkedinLink" => $company_Info->linkedinlink,
                        "Logo" => $company_Info->strLogo
                    );
                } else {
                    $company_Info = CompanyInfo::where('iCompanyInfoId', '=', 1)->first();
                    $dataInfo = array(
                        "CompanyName" => $company_Info->strCompanyName,
                        "CompanyEmail" => $company_Info->EmailId,
                        "instaLink" => $company_Info->instaLink,
                        "twitterLink" => $company_Info->twitterlink,
                        "facebookLink" => $company_Info->facebookLink,
                        "linkedinLink" => $company_Info->linkedinlink,
                        "Logo" => $company_Info->strLogo
                    );
                }

                // $msg = array(
                //     'FromMail' => $SendEmailDetails->strFromMail,
                //     'Title' => $SendEmailDetails->strTitle,
                //     'ToEmail' => $CompanyMaster['EmailId'],
                //     'Subject' => "Add new company profile request"
                // );

                // $mail = Mail::send('emails.alertMail', ['Company' => $request->othrcompanyprofile, "data" => $dataInfo], function ($message) use ($msg) {
                //     $message->from($msg['FromMail'], $msg['Title']);
                //     $message->to($msg['ToEmail'])->subject($msg['Subject']);
                // });
                $iCompanyProfileId = -1;
            } else {
                $iCompanyProfileId = $request->iCompanyProfileId ? $request->iCompanyProfileId : 0;
            }

            if ($request->iCompanyId == 'Other' && $request->othrcompanyname != '') {
                $companyData = array(
                    "CompanyName" => $request->othrcompanyname,
                    "email" => $request->CustomerEmailCompany,
                    "iCompanyId" => $request->OemCompannyId,
                    "iEntryBy" => $session
                );
                $companyId = DB::table('companyclient')->insertGetId($companyData);
            } else {
                $companyId = $request->iCompanyId ? $request->iCompanyId : 0;
            }

            $iSubComponentId = "";
            foreach ($request->iSubComponentId as $SubComponentId) {
                $iSubComponentId .= $SubComponentId . ",";
            }
            $iSubComponentId = rtrim($iSubComponentId, ",");
            //$iSubComponentId = $request->iSubComponentId;
            //dd($request);
            $ticket = array(
                "iCustomerComplainUserId" => 0,
                "CustomerEmail" => $request->CustomerEmail,
                //"OemCompannyId" => $request->OemCompannyId ? $request->OemCompannyId : 0,
                "iCompanyId" => $companyId,
                "iCompanyProfileId" => $iCompanyProfileId,
                "otherCompanyProfile" => $request->othrcompanyprofile ? $request->othrcompanyprofile : '',
                "CustomerEmailCompany" => $request->CustomerEmailCompany,
                "OtherInformation" => $request->OtherInformation,
                "iDistributorId" => $request->iDistributorId ? $request->iDistributorId : 0,
                "ProjectName" => $request->ProjectName,
                "iStateId" => $request->iStateId ? $request->iStateId : 0,
                "iCityId" => $request->iCityId ? $request->iCityId : 0,
                "iCallThrough" => $request->iCallThrough,
                "UserDefiine1" => $request->UserDefiine1,
                "iSystemId" => $request->iSystemId ? $request->iSystemId : 0,
                "iComnentId" => $request->iComponentId ? $request->iComponentId : 0,
                //"iSubComponentId" => $request->iSubComponentId ? $request->iSubComponentId : 0,
                "iSubComponentId" => $iSubComponentId ?? 0,
                "iSupportType" => $request->iSupportType ? $request->iSupportType : 0,
                "issue" => $request->issue,
                "Resolutiondetail" => $request->Resolutiondetail,
                "iResolutionCategoryId" => $request->iResolutionCategoryId ? $request->iResolutionCategoryId : 0,
                "iIssueTypeId" => $request->iIssueTypeId ? $request->iIssueTypeId : 0,
                "CallerCompetencyId" => $request->CallerCompetencyId ? $request->CallerCompetencyId : 0,
                "iTicketEditedBy" => $session,
                "comments" => $request->comments ? $request->comments : "",
                "EditedDateTime" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()

            );
            $Ticket = DB::table('ticketmaster')->where('iTicketId', $request->iTicketId)->update($ticket);
            if ($Ticket == 1) {
                Session::flash('Success', 'Ticket Updated Succefully!');
            } else {
                Session::flash('Error', 'Error in update data!');
            }
            //return redirect()->route('complaint.index', compact('CompanyMaster', 'statemasters', 'citymasters'))->with('Success', 'ticket Created Successfully.');
            return redirect()->back()->with('Success', 'Ticket Updated Successfully.');
        } else {
            return redirect()->back()->with('error', "Error in update data!");
        }
    }
}
