<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Reference;
use App\Models\CompanyMaster;
use App\Models\Component;
use App\Models\SubComponent;
use App\Models\System;
use App\Models\RefDocument;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\CallAttendent;
use App\Models\WlUser;
use App\Models\MultipleCompanyRole;

class CallAttendantReferenceController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 3) {
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

            $search_component = $request->iComponentId;
            $search_sub_component = $request->iSubComponentId;
            $search_system = $request->iSystemId;
            if (isset($request->searchRef)) {
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
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1])
                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->when($request->iComponentId, fn($query, $search_component) => $query->where('iComponentId', $search_component))
                ->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1])
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
                if (isset($userID->iCompanyId) && $userID->iCompanyId != 0) {
                    $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, 'companymaster.iStatus' => 1])
                        //->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                        ->whereIn('iCompanyId', function ($query) {
                            $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                        })
                        ->orderBy('strOEMCompanyName', 'ASC')
                        ->get();
                        if($CompanyMaster->isEmpty()){
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
                } else if(isset($userID->iOEMCompany) && $userID->iOEMCompany == 0){
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
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1])->orderBy('strSystem', 'ASC')
                ->when($search_company, fn($query, $search_company) => $query->where('iCompanyId', $search_company))
                ->get();
            $Faq = Reference::select('reference.*', 'component.strComponent', 'subcomponent.strSubComponent', 'system.strSystem', 'companymaster.strOEMCompanyName')
                ->orderBy('iRefId', 'DESC')
                ->join('component', 'component.iComponentId', '=', 'reference.iComponentId', 'left outer')
                ->join('subcomponent', 'subcomponent.iSubComponentId', '=', 'reference.iSubComponentId', 'left outer')
                ->join('system', 'system.iSystemId', '=', 'reference.iSystemId', 'left outer')
                ->join('companymaster', 'companymaster.iCompanyId', '=', 'reference.iCompanyId', 'left outer')
                ->when($request->iComponentId, fn($query, $search_component) => $query->where('reference.iComponentId', $search_component))
                ->when($request->iSubComponentId, fn($query, $search_sub_component) => $query->where('reference.iSubComponentId', $search_sub_component))
                ->when($request->iSystemId, fn($query, $search_system) => $query->where('reference.iSystemId', $search_system))
                ->when($search_company, fn($query, $search_company) => $query->where('reference.iCompanyId', $search_company))
                ->when($request->searchWord, fn($query, $searchWord) => $query->where('reference.strRefTitle', "LIKE", "%" . $searchWord . "%"))
                // ->when($request->searchText, fn ($query, $searchText) => $query->where('reference.strRefTitle', $searchText))
                ->where(['reference.isDelete' => 0, 'reference.iStatus' => 1]);
                if ($userID) {
                    $Faq->when($userID->iCompanyId, fn($query, $OemCompannyId) => $query->where('reference.iCompanyId', $OemCompannyId))
                        ->when($userID->iOEMCompany, fn($query, $OemCompannyId) => $query->where('reference.iCompanyId', $OemCompannyId));
                }

                if (isset($userID->iCompanyId) && $userID->iCompanyId == 0) {
                    $Faq->whereIn('reference.iCompanyId', function ($query) {
                        $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                    });
                } else if (isset($userID->iOEMCompany) && $userID->iOEMCompany == 0) {
                    $Faq->whereIn('reference.iCompanyId', function ($query) {
                        $query->select('multiplecompanyrole.iOEMCompany')->from('multiplecompanyrole')->where(["userid" => Auth::user()->id]);
                    });
                }
                $Faqs = $Faq->get();

            foreach ($Faqs as $faq) {
                $faqDocuments = RefDocument::where(['isDelete' => 0, 'iStatus' => 1, 'iRefId' => $faq->iRefId])->orderBy('iRefId', 'DESC')->get();
                $faq['gallery'] = $faqDocuments;
            }

            $postarray = array('OemCompannyId' => '6');
            foreach ($request->request as $key => $value) {

                $postarray[$key] = $value;
            }
            return view('call_attendant.reference.index', compact('Faqs', 'componentLists', 'subcomponents', 'CompanyMaster', 'systemLists', 'postarray'));
        } else {
            return redirect()->route('home');
        }
    }

    public function downloadDoc(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $refId = $request->iRefId;
            $ref_detail = Reference::where('iRefId', $refId)->first();

            $refDocument = RefDocument::where(['isDelete' => 0, 'iStatus' => 1, 'iRefId' => $refId])
                ->whereIn('iDocumentType', $request->type)
                ->get();

            $destinationPath = array();
            foreach ($refDocument as $value) {
                if ($value->iDocumentType == 1) {

                    $destinationPath[] = 'RefDocument/Document/' . $value->strFileName;
                    //  $destinationPath[] =   $root . '/RefDocument/Document/' . $value->strFileName;
                } elseif ($value->iDocumentType == 2) {
                    $destinationPath[] = 'RefDocument/Image/' . $value->strFileName;
                    //$destinationPath[] = $root . '\\RefDocument\Image\\' . $value->strFileName;
                } else {
                    $destinationPath[] = 'RefDocument/Video/' . $value->strFileName;
                    //$destinationPath[] = $root . "\\RefDocument\Video\\" . $value->strFileName;
                }
            }
            $fileName = str_replace(' ', '_', $ref_detail->strRefTitle) . '_' . date('dmY') . '.zip';
            $zip = new ZipArchive('RefDocument/' . $fileName);

            // $public_dir = public_path() . DIRECTORY_SEPARATOR . 'uploads/post/zip';
            // $file_path = public_path() . DIRECTORY_SEPARATOR . 'uploads/post';


            if ($zip->open('RefDocument/' . $fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

                $files = $destinationPath; //passing the above array
                foreach ($files as  $key => $value) {

                    $relativeName  = basename($value);
                    if (file_exists($value)) {
                        $zip->addFile($value, $relativeName);
                    }
                }
                $zip->close();
            }
            if (file_exists('RefDocument/' . $fileName)) {
                Session::flash('Success', 'File Downloaded succesfully');
                return     asset('RefDocument/' . $fileName);
            } else {
                return redirect()->route('callattendantreference.index')->with('Error', 'Invalid Request.');
            }
        } else {
            return redirect()->route('home');
        }
    }
    public function getContenType(Request $request)
    {
        $refDocument = Reference::where(['isDelete' => 0, 'iStatus' => 1, 'iRefId' => $request->Id])->first();

        return json_encode(explode(',', $refDocument->strContentType));
    }

    public function  emailDoc(Request $request)
    {
        if (Auth::User()->role_id == 3) {
            $refId = $request->iRefId;
            $ref_detail = Reference::where('iRefId', $refId)->first();

            $refDocument = RefDocument::where(['isDelete' => 0, 'iStatus' => 1, 'iRefId' => $refId])
                ->whereIn('iDocumentType', $request->type)
                ->get();

            $destinationPath = array();
            foreach ($refDocument as $value) {
                if ($value->iDocumentType == 1) {

                    $destinationPath[] = 'RefDocument/Document/' . $value->strFileName;
                    //  $destinationPath[] =   $root . '/RefDocument/Document/' . $value->strFileName;
                } elseif ($value->iDocumentType == 2) {
                    $destinationPath[] = 'RefDocument/Image/' . $value->strFileName;
                    //$destinationPath[] = $root . '\\RefDocument\Image\\' . $value->strFileName;
                } else {
                    $destinationPath[] = 'RefDocument/Video/' . $value->strFileName;
                    //$destinationPath[] = $root . "\\RefDocument\Video\\" . $value->strFileName;
                }
            }
            $fileName =  str_replace(' ', '_', $ref_detail->strRefTitle) . '_' . date('dmY') . '.zip';
            $zip = new ZipArchive();

            // $public_dir = public_path() . DIRECTORY_SEPARATOR . 'uploads/post/zip';
            // $file_path = public_path() . DIRECTORY_SEPARATOR . 'uploads/post';


            if ($zip->open('RefDocument/' . $fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

                $files = $destinationPath; //passing the above array
                foreach ($files as  $key => $value) {
                    $relativeName  = basename($value);
                    if (file_exists($value)) {
                        $zip->addFile($value, $relativeName);
                    }
                }
                $zip->close();
            }
            //email send code
            if (file_exists('RefDocument/' . $fileName)) {
                $SendEmailDetails = DB::table('sendemaildetails')
                    ->where(['id' => 8])
                    ->first();



                $companyInfo = CompanyInfo::where('iCompanyId', '=', $ref_detail->iCompanyId)->first();
                if (!empty($companyInfo)) {
                    $data = array(
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
                    $msg = array(
                        'FromMail' => $companyInfo->EmailId,
                        'Title' => $companyInfo->strCompanyName,
                        'ToEmail' =>  explode(',', $request->emailids),
                        'Subject' => $SendEmailDetails->strSubject
                    );
                } else {
                    // Mas Solution and link
                    $companyInfo = CompanyInfo::where('iCompanyInfoId', '=', '1')->first();
                    $data = array(
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
                    $msg = array(
                        'FromMail' => $SendEmailDetails->strFromMail,
                        'Title' => $SendEmailDetails->strTitle,
                        'ToEmail' =>  explode(',', $request->emailids),
                        'Subject' => $SendEmailDetails->strSubject
                    );
                }
                $attachmentFile = 'RefDocument/' . $fileName;
                $mail = Mail::send('emails.attatchmentMail', ['data' => $data], function ($message) use ($msg, $attachmentFile) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                    $message->attach($attachmentFile);
                });
                if ($mail) {
                    return redirect()->route('callattendantreference.index')->with('success', 'Email send successfully.');
                } else {
                    return redirect()->route('callattendantreference.index')->with('Error', 'Invalid Request.');
                }
            } else {
                return redirect()->route('callattendantreference.index')->with('Error', 'Invalid Request.');
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function openDoc(Request $request, $id)
    {

        $root = $_SERVER['DOCUMENT_ROOT'];

        $faqDocuments = RefDocument::where(["iRefDocumentId" => $id])->first();
        if ($faqDocuments->iDocumentType == 1) {
            $destinationPath =  asset('RefDocument/Document/') . '/' . $faqDocuments->strFileName;
            $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);
            if ($ext == 'pdf')
                echo   "<iframe src='" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
            else
                echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        } else if ($faqDocuments->iDocumentType == 2) {
            $destinationPath =  asset('RefDocument/Image/') . '/' . $faqDocuments->strFileName;
            echo  "<img src='" . $destinationPath  . "'  class='d-block w-100' style=' width: 70%;'>";
        } else {
            $destinationPath =  asset('RefDocument/Video/') . '/' . $faqDocuments->strFileName;
            echo  ' <video controls="" name="media" style=" width: 70%;">
            <source
                src="' . $destinationPath . '"
                type="video/mp4">
        </video>';
        }
    }
    public function openDocuments(Request $request)
    {

        $root = $_SERVER['DOCUMENT_ROOT'];
        $refId = $request->iRefId;
        $faqDocumentlist =  RefDocument::select('iRefDocumentId')
            ->where(['isDelete' => 0, 'iStatus' => 1, 'iRefId' => $refId])
            ->whereIn('iDocumentType', $request->type)
            ->get();
        // foreach ($faqDocumentlist  as $faqDocuments) {
        //     return redirect()->away($faqDocuments->iRefDocumentId . '/openDoc')->with('_blank');;
        //     if ($faqDocuments->iDocumentType == 1) {

        //         // $destinationPath =  asset('RefDocument/Document/') . '/' . $faqDocuments->strFileName;
        //         // $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);
        //         // if ($ext == 'pdf')
        //         //     echo   "<iframe src='" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        //         // else
        //         //     echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        //     } else if ($faqDocuments->iDocumentType == 2) {
        //         // $destinationPath =  asset('RefDocument/Image/') . '/' . $faqDocuments->strFileName;
        //         // echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        //     } else {
        //         // $destinationPath =  asset('RefDocument/Video/') . '/' . $faqDocuments->strFileName;
        //         // echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        //     }
        // }
        return $faqDocumentlist;
    }
}
