<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;
use App\Models\SubComponent;
use App\Models\Component;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\CompanyMaster;
use App\Models\FaqDocument;
use App\Models\System;
use App\Models\infoTable;
use Auth;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $search_component = $request->search_component;
            $search_sub_component = $request->search_sub_component;
            $iSystemId = $request->iSystemId;
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            //$Faq = Faq::orderBy('iFAQId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strComponent', 'ASC')
                ->get();
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $CompanyMaster->iCompanyId])->distinct()->orderBy('strSystem', 'ASC')->get();
            $Faqs = Faq::select('faqmaster.*', 'component.strComponent', 'subcomponent.strSubComponent', 'system.strSystem')->orderBy('iFAQId', 'DESC')
                ->join('component', 'component.iComponentId', '=', 'faqmaster.iComponentId', 'left outer')
                ->join('subcomponent', 'subcomponent.iSubComponentId', '=', 'faqmaster.iSubComponentId', 'left outer')
                ->join('system', 'system.iSystemId', '=', 'faqmaster.iSystemId', 'left outer')
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('faqmaster.iSystemId', $iSystemId))
                ->when($request->search_component, fn ($query, $search_component) => $query->where('faqmaster.iComponentId', $search_component))
                ->when($request->search_sub_component, fn ($query, $search_sub_component) => $query->where('faqmaster.iSubComponentId', $search_sub_component))
                ->where(['faqmaster.isDelete' => 0, 'faqmaster.iStatus' => 1, 'faqmaster.iCompanyId' => $CompanyMaster->iCompanyId])->get();

            return view('wladmin.Faq.index', compact('Faqs', 'componentLists', 'subcomponents', 'search_component', 'search_sub_component', 'systems', 'iSystemId'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            //$Faq = Faq::orderBy('iFAQId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1, "system.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strSystem', 'ASC')
                ->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strComponent', 'ASC')
                ->get();
            return view('wladmin.Faq.add', compact('subcomponents', 'componentLists', 'CompanyMaster', 'systemLists'));
        } else {
            return redirect()->route('home');
        }
    }

    public function getsubcomponent(Request $request)
    {
        $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

        $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iComponentId" => $request->iComponentId])
            ->orderBy('strSubComponent', 'ASC')
            ->get();

        $html = "";
        if (count($subcomponents) > 0) {
            $html .= '<option label="Please Select" value="">-- Select --</option>';
            foreach ($subcomponents as $subcomponent) {
                $html .= '<option value="' . $subcomponent->iSubComponentId . '">' . $subcomponent->strSubComponent . '</option>';
            }
        } else {
            $html .= '<option label="Please Select" value="">No record Found</option>';
        }
        echo $html;
    }

    public function store(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $iFAQId = 0;
            $faqArr = array(
                "iCompanyId" => $request->iCompanyId,
                "iComponentId" => $request->iComponentId ? $request->iComponentId : 0,
                "iSubComponentId" => $request->iSubComponentId ? $request->iSubComponentId : 0,
                "iSystemId" => $request->iSystemId,
                "strFAQTitle" => $request->strFAQTitle,
                "strFAQDescription" => $request->strFAQDescription,
                "strEntryDate" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            $FAQId = Faq::create($faqArr);
            $iFAQId = $FAQId->id;

            if ($request->hasFile('strDocument')) {
                $img = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Document/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strDocument') as $docfile) {

                    $image = $docfile;
                    $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $img);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 1,
                        "strFileName" => $img,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }


            if ($request->hasFile('strImages')) {
                $imgName = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Image/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strImages') as $imagefile) {
                    $image = $imagefile;
                    $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $imgName);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 2,
                        "strFileName" => $imgName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }
            if ($request->hasFile('strVideo')) {
                $strVideoName = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Video/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strVideo') as $imagefile) {

                    $Video = $imagefile;
                    $strVideoName = pathinfo($Video->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $Video->getClientOriginalExtension();

                    $Video->move($destinationPath, $strVideoName);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 3,
                        "strFileName" => $strVideoName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }
            $userdata = User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "faqs",
                'tableAutoId'    => $iFAQId,
                'tableMainField'  => "Company Faqs",
                'action'     => "Inserted",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);
            return redirect()->route('faq.index')->with('Success', 'Faq Created Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function editview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strComponent', 'ASC')
                ->get();
            $Faqs = Faq::orderBy('iFAQId', 'DESC')
                ->where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $CompanyMaster->iCompanyId, "iFAQId" => $id])->first();
            $faqDocuments = FaqDocument::where(['iDocumentType' => 1, "iFAQId" => $id])->get();
            $faqImages = FaqDocument::where(['iDocumentType' => 2, "iFAQId" => $id])->get();
            $faqVideos = FaqDocument::where(['iDocumentType' => 3, "iFAQId" => $id])->get();

            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1, "system.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strSystem', 'ASC')
                ->get();
            return view('wladmin.Faq.edit', compact('subcomponents', 'componentLists', 'CompanyMaster', 'Faqs', 'systemLists', 'faqDocuments', 'faqImages', 'faqVideos'));
        } else {
            return redirect()->route('home');
        }
    }
    public function infoview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $Faqs = Faq::orderBy('iFAQId', 'DESC')
                ->select('faqmaster.*', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent',)
                ->join('system', 'system.iSystemId', '=', 'faqmaster.iSystemId', 'left outer')
                ->join('component', 'component.iComponentId', '=', 'faqmaster.iComponentId', 'left outer')
                ->join('subcomponent', 'subcomponent.iSubComponentId', '=', 'faqmaster.iSubComponentId', 'left outer')
                ->where(['faqmaster.isDelete' => 0, 'faqmaster.iStatus' => 1, "faqmaster.iFAQId" => $id])
                ->first();
            $faqDocuments = FaqDocument::where(['iDocumentType' => 1, "iFAQId" => $id])->get();
            $faqImages = FaqDocument::where(['iDocumentType' => 2, "iFAQId" => $id])->get();
            $faqVideos = FaqDocument::where(['iDocumentType' => 3, "iFAQId" => $id])->get();

            $infoTables = infoTable::where(["tableName" => "faqs", "tableAutoId" => $id])->orderBy('id', 'Desc')->limit(10)->get();

            return view('wladmin.Faq.info', compact('Faqs', 'faqDocuments', 'faqImages', 'faqVideos', 'infoTables'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $faqArr = array(
                "iCompanyId" => $request->iCompanyId,
                "iComponentId" => $request->iComponentId ? $request->iComponentId : 0,
                "iSubComponentId" => $request->iSubComponentId ? $request->iSubComponentId : 0,
                "iSystemId" => $request->iSystemId,
                "strFAQTitle" => $request->strFAQTitle,
                "strFAQDescription" => $request->strFAQDescription,
                "strEntryDate" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            Faq::where('iFAQId', '=', $request->iFAQId)->update($faqArr);
            $iFAQId = $request->iFAQId;
            $img = "";
            if ($request->hasFile('strDocument')) {

                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Document/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('strDocument') as $docfile) {
                    $image = $docfile;
                    $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();

                    $image->move($destinationPath, $img);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 1,
                        "strFileName" => $img,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }

            if ($request->hasFile('strImages')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Image/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $imgName = "";
                foreach ($request->file('strImages') as $imagefile) {
                    $image = $imagefile;
                    $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $imgName);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 2,
                        "strFileName" => $imgName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }
            if ($request->hasFile('strVideo')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/FaqDocument/Video/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $strVideoName = "";
                foreach ($request->file('strVideo') as $imagefile) {
                    $Video = $imagefile;
                    $strVideoName = pathinfo($Video->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $Video->getClientOriginalExtension();
                    $Video->move($destinationPath, $strVideoName);
                    $docArr = array(
                        "iFAQId" => $iFAQId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 3,
                        "strFileName" => $strVideoName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    FaqDocument::create($docArr);
                }
            }
            $userdata = User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "faqs",
                'tableAutoId'    => $iFAQId,
                'tableMainField'  => "Company Faqs",
                'action'     => "Update",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);
            return redirect()->route('faq.index')->with('Success', 'Faq Updated Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if (Auth::User()->role_id == 2) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationPath = $root . '/FaqDocument/Video/';
            $faqDocuments = FaqDocument::where(['iDocumentType' => 1, "iFAQId" => $Id])->first();
            if (!empty($faqDocuments)) {
                if ($faqDocuments->strFileName != "") {
                    if (file_exists($destinationPath . $faqDocuments->strFileName)) {
                        unlink($destinationPath . $faqDocuments->strFileName);
                    }
                }
                FaqDocument::where(['iDocumentType' => 1, "iFAQId" => $Id])->delete();
            }
            $faqDocuments = FaqDocument::where(['iDocumentType' => 2, "iFAQId" => $Id])->get();
            if (!empty($faqDocuments->isEmpty)) {
                foreach ($faqDocuments as $faqDocument) {
                    if ($faqDocument->strFileName != "") {
                        if (file_exists($destinationPath . $faqDocument->strFileName)) {
                            unlink($destinationPath . $faqDocument->strFileName);
                        }
                    }
                    FaqDocument::where(['iDocumentType' => 2, "iFAQDocumentId" => $faqDocument->iFAQDocumentId])->delete();
                }
            }
            $faqDocuments = FaqDocument::where(['iDocumentType' => 3, "iFAQId" => $Id])->get();
            if (!empty($faqDocuments)) {
                foreach ($faqDocuments as $faqDocument) {
                    if ($faqDocument->strFileName != "") {
                        if (file_exists($destinationPath . $faqDocument->strFileName)) {
                            unlink($destinationPath . $faqDocument->strFileName);
                        }
                    }
                    FaqDocument::where(['iDocumentType' => 3, "iFAQDocumentId" => $faqDocument->iFAQDocumentId])->delete();
                }
            }
            Faq::where('iFAQId', '=', $Id)->delete();

            return redirect()->route('faq.index')->with('Success', 'Faq Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }

    public function openDocument(Request $request, $id)
    {
        $root = $_SERVER['DOCUMENT_ROOT'];
        $faqDocuments = FaqDocument::where(["iFAQDocumentId" => $id])->first();
        if ($faqDocuments->iDocumentType == 1) {
            $destinationPath =  asset('FaqDocument/Document/') . '/' . $faqDocuments->strFileName;
            $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);
            if ($ext == 'pdf')
                echo   "<iframe src='" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
            else
                echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        } else if ($faqDocuments->iDocumentType == 2) {
            $destinationPath =  asset('FaqDocument/Image/') . '/' . $faqDocuments->strFileName;
            echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        } else {
            $destinationPath =  asset('FaqDocument/Video/') . '/' . $faqDocuments->strFileName;
            echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        }
    }
    public function deletedoc(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $faqDocuments = FaqDocument::where(["iFAQDocumentId" => $request->id])->first();
            if ($faqDocuments->iDocumentType == 1) {
                if ($faqDocuments->strFileName != "") {
                    $destinationPath =  asset('FaqDocument/Document/') . '/' . $faqDocuments->strFileName;
                    if (file_exists($destinationPath)) {
                        unlink($destinationPath);
                    }
                }
                FaqDocument::where(['iDocumentType' => 1, "iFAQDocumentId" => $faqDocuments->iFAQDocumentId])->delete();
            } else if ($faqDocuments->iDocumentType == 2) {

                if ($faqDocuments->strFileName != "") {
                    $destinationPath =  asset('FaqDocument/Image/') . '/' . $faqDocuments->strFileName;
                    if (file_exists($destinationPath)) {
                        unlink($destinationPath);
                    }
                }
                FaqDocument::where(['iDocumentType' => 2, "iFAQDocumentId" => $faqDocuments->iFAQDocumentId])->delete();
            } else {

                if ($faqDocuments->strFileName != "") {
                    $destinationPath =  asset('FaqDocument/Video/') . '/' . $faqDocuments->strFileName;
                    if (file_exists($destinationPath)) {
                        unlink($destinationPath);
                    }
                }
                FaqDocument::where(['iDocumentType' => 3, "iFAQDocumentId" => $faqDocuments->iFAQDocumentId])->delete();
            }
            echo 0;
        } else {
            return redirect()->route('home');
        }
    }
}
