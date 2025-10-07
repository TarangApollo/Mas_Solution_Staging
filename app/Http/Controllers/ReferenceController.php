<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;
use Illuminate\Support\Facades\DB;
use App\Models\SubComponent;
use App\Models\Component;
use App\Models\System;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyMaster;
use App\Models\RefDocument;
use App\Models\infoTable;
use App\Models\User;
use Auth;

class ReferenceController extends Controller
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
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strComponent', 'ASC')
                ->get();
            $references = Reference::select('reference.*', 'component.strComponent', 'subcomponent.strSubComponent', 'system.strSystem')
                ->join('component', 'component.iComponentId', '=', 'reference.iComponentId', 'left outer')
                ->join('subcomponent', 'subcomponent.iSubComponentId', '=', 'reference.iSubComponentId', 'left outer')
                ->join('system', 'system.iSystemId', '=', 'reference.iSystemId', 'left outer')
                ->when($request->iSystemId, fn ($query, $iSystemId) => $query->where('reference.iSystemId', $iSystemId))
                ->when($request->search_component, fn ($query, $search_component) => $query->where('reference.iComponentId', $search_component))
                ->when($request->search_sub_component, fn ($query, $search_sub_component) => $query->where('reference.iSubComponentId', $search_sub_component))
                ->orderBy('iRefId', 'DESC')
                ->where(['reference.isDelete' => 0, 'reference.iStatus' => 1, 'reference.iCompanyId' => $CompanyMaster->iCompanyId])->get();
            $refdocuments = RefDocument::where(["iCompanyId" => $CompanyMaster->iCompanyId])->get();
            $systems = System::where(['isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => $CompanyMaster->iCompanyId])->distinct()->orderBy('strSystem', 'ASC')->get();
            return view('wladmin.reference.index', compact('references', 'subcomponents', 'componentLists', 'search_sub_component', 'search_component', 'CompanyMaster', 'refdocuments', 'systems', 'iSystemId'));
        } else {
            return redirect()->route('home');
        }
    }

    public function createview()
    {
        if (Auth::User()->role_id == 2) {
            //$Reference = Reference::orderBy('iRefId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1])->get();
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1, "system.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strSystem', 'ASC')
                ->get();
            $subcomponents = SubComponent::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => $CompanyMaster->iCompanyId])->orderBy('strSubComponent', 'ASC')->get();
            $componentLists = Component::where(['component.isDelete' => 0, 'component.iStatus' => 1, "component.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strComponent', 'ASC')
                ->get();
            return view('wladmin.reference.add', compact('CompanyMaster', 'subcomponents', 'componentLists', 'systemLists'));
        } else {
            return redirect()->route('home');
        }
    }

    public function store(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $iRefId = 0;
            $ContentType = "";
            foreach ($request->strContentType as $strContentType) {
                $ContentType .= $strContentType . ",";
            }
            $ContentType = rtrim($ContentType, ',');
            $RefArr = array(
                "iCompanyId" => $request->iCompanyId,
                "iComponentId" => $request->iComponentId,
                "iSubComponentId" => $request->iSubComponentId ? $request->iSubComponentId : 0,
                "iSystemId" => $request->iSystemId,
                "strRefTitle" => $request->strRefTitle,
                "strContentType" => $ContentType,
                "strEntryDate" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );

            $RefId = Reference::create($RefArr);
            $iRefId = $RefId->id;

            if ($request->hasFile('strDocument')) {
                $img = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/RefDocument/Document/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strDocument') as $docfile) {

                    $image = $docfile;
                    $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $img);
                    $docArr = array(
                        "iRefId" => $iRefId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 1,
                        "strFileName" => $img,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    RefDocument::create($docArr);
                }
            }


            if ($request->hasFile('strImages')) {
                $imgName = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/RefDocument/Image/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strImages') as $imagefile) {

                    $image = $imagefile;
                    $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time()  . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $imgName);
                    $docArr = array(
                        "iRefId" => $iRefId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 2,
                        "strFileName" => $imgName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    RefDocument::create($docArr);
                }
            }
            if ($request->hasFile('strVideo')) {
                $strVideoName = "";
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/RefDocument/Video/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                foreach ($request->file('strVideo') as $imagefile) {

                    $Video = $imagefile;
                    $strVideoName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $Video->getClientOriginalExtension();
                    $Video->move($destinationPath, $strVideoName);
                    $docArr = array(
                        "iRefId" => $iRefId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 3,
                        "strFileName" => $strVideoName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    RefDocument::create($docArr);
                }
            }
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $userdata = User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "references",
                'tableAutoId'    => $iRefId,
                'tableMainField'  => "Company Reference Information",
                'action'     => "Inserted",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);
            return redirect()->route('reference.index')->with('Success', 'Reference Created Successfully.');
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
            $systemLists = System::where(['system.isDelete' => 0, 'system.iStatus' => 1, "system.iCompanyId" => $CompanyMaster->iCompanyId])
                ->orderBy('strSystem', 'ASC')
                ->get();
            $Reference = Reference::where(['iStatus' => 1, 'isDelete' => 0, "iCompanyId" => $CompanyMaster->iCompanyId, 'iRefId' => $id])->first();
            $refDocuments = RefDocument::where(['iDocumentType' => 1, "iRefId" => $id])->get();
            $refImages = RefDocument::where(['iDocumentType' => 2, "iRefId" => $id])->get();
            $refVideos = RefDocument::where(['iDocumentType' => 3, "iRefId" => $id])->get();

            return view('wladmin.reference.edit', compact('subcomponents', 'componentLists', 'CompanyMaster', 'Reference', 'systemLists', 'refDocuments', 'refImages', 'refVideos'));
        } else {
            return redirect()->route('home');
        }
    }

    public function infoview(Request $request, $id)
    {
        if (Auth::User()->role_id == 2) {
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $CompanyMaster = CompanyMaster::where(['companymaster.isDelete' => 0, "iCompanyId" => Session::get('CompanyId')])
                ->orderBy('strOEMCompanyName', 'ASC')
                ->first();

            $References = Reference::where(['reference.iStatus' => 1, 'reference.isDelete' => 0, "reference.iCompanyId" => $CompanyMaster->iCompanyId, 'iRefId' => $id])
                ->select('reference.*', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent',)
                ->join('system', 'system.iSystemId', '=', 'reference.iSystemId', 'left outer')
                ->join('component', 'component.iComponentId', '=', 'reference.iComponentId', 'left outer')
                ->join('subcomponent', 'subcomponent.iSubComponentId', '=', 'reference.iSubComponentId', 'left outer')
                ->first();
            $refDocuments = RefDocument::where(['iDocumentType' => 1, "iRefId" => $id])->get();
            $refImages = RefDocument::where(['iDocumentType' => 2, "iRefId" => $id])->get();
            $refVideos = RefDocument::where(['iDocumentType' => 3, "iRefId" => $id])->get();

            $infoTables = infoTable::where(["tableName" => "references", "tableAutoId" => $id])->orderBy('id', 'Desc')->limit(10)->get();
            return view('wladmin.reference.info', compact('References', 'refDocuments', 'refImages', 'refVideos', 'infoTables'));
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $ContentType = "";

            foreach ($request->strContentType as $strContentType) {
                $ContentType .= $strContentType . ",";
            }
            $ContentType = rtrim($ContentType, ',');
            $refArr = array(
                "iCompanyId" => $request->iCompanyId,
                "iComponentId" => $request->iComponentId ? $request->iComponentId : 0,
                "iSubComponentId" => ($request->iSubComponentId) ? $request->iSubComponentId : 0,
                "iSystemId" => $request->iSystemId,
                "strRefTitle" => $request->strRefTitle,
                "strContentType" => $ContentType,
                "strEntryDate" => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            Reference::where('iRefId', '=', $request->iRefId)->update($refArr);
            $iRefId = $request->iRefId;
            $img = "";
            if ($request->hasFile('strDocument')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/RefDocument/Document/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }


                foreach ($request->file('strDocument') as $docfile) {
                    $image = $docfile;
                    $img = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $img);
                    $docArr = array(
                        "iRefId" => $iRefId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 1,
                        "strFileName" => $img,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    RefDocument::create($docArr);
                }
            }

            if ($request->hasFile('strImages')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/RefDocument/Image/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $imgName = "";
                foreach ($request->file('strImages') as $imagefile) {
                    $image = $imagefile;
                    $imgName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time()  . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $imgName);
                    $imgArr = array(
                        "iRefId" => $iRefId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 2,
                        "strFileName" => $imgName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    //print_r($imgArr);
                    RefDocument::create($imgArr);
                }
            }
            if ($request->hasFile('strVideo')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $destinationPath = $root . '/RefDocument/Video/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $strVideoName = "";
                foreach ($request->file('strVideo') as $imagefile) {
                    $Video = $imagefile;
                    $strVideoName = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $Video->getClientOriginalExtension();
                    $Video->move($destinationPath, $strVideoName);

                    $docArr = array(
                        "iRefId" => $iRefId,
                        "iCompanyId" => $request->iCompanyId,
                        "iDocumentType" => 3,
                        "strFileName" => $strVideoName,
                        "strEntryDate" => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    RefDocument::create($docArr);
                }
            }
            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $userdata = User::whereId($session)->first();
            $infoArr = array(
                'tableName'    => "references",
                'tableAutoId'    => $iRefId,
                'tableMainField'  => "Company Reference Information",
                'action'     => "Update",
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);
            return redirect()->route('reference.index')->with('Success', 'Reference Updated Successfully.');
        } else {
            return redirect()->route('home');
        }
    }

    public function openDocument(Request $request, $id)
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
            echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        } else {
            $destinationPath =  asset('RefDocument/Video/') . '/' . $faqDocuments->strFileName;
            echo  "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . $destinationPath  . "' width='100%' height='100%' frameborder='0'></iframe>";
        }
    }
    public function deletedoc(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $faqDocuments = RefDocument::where(["iRefDocumentId" => $request->id])->first();

            if ($faqDocuments->iDocumentType == 1) {
                if ($faqDocuments->strFileName != "") {
                    $destinationPath =  asset('RefDocument/Document/') . '/' . $faqDocuments->strFileName;
                    if (file_exists($destinationPath)) {
                        unlink($destinationPath);
                    }
                }
                RefDocument::where(['iDocumentType' => 1, "iRefDocumentId" => $faqDocuments->iRefDocumentId])->delete();
            } else if ($faqDocuments->iDocumentType == 2) {

                if ($faqDocuments->strFileName != "") {
                    $destinationPath =  asset('RefDocument/Image/') . '/' . $faqDocuments->strFileName;
                    if (file_exists($destinationPath)) {
                        unlink($destinationPath);
                    }
                }
                RefDocument::where(['iDocumentType' => 2, "iRefDocumentId" => $faqDocuments->iRefDocumentId])->delete();
            } else {

                if ($faqDocuments->strFileName != "") {
                    $destinationPath =  asset('RefDocument/Video/') . '/' . $faqDocuments->strFileName;
                    if (file_exists($destinationPath)) {
                        unlink($destinationPath);
                    }
                }
                RefDocument::where(['iDocumentType' => 3, "iRefDocumentId" => $faqDocuments->iRefDocumentId])->delete();
            }
            echo 0;
        } else {
            return redirect()->route('home');
        }
    }

    public function delete($Id)
    {
        if (Auth::User()->role_id == 2) {
            $RefDocuments = RefDocument::where(['iDocumentType' => 1, "iRefId" => $Id])->first();
            if (!empty($RefDocuments)) {
                if ($RefDocuments->strFileName != "") {
                    $root = $_SERVER['DOCUMENT_ROOT'];
                    $destinationPath = $root . '/RefDocument/Document/';
                    if (file_exists($destinationPath . $RefDocuments->strFileName)) {
                        unlink($destinationPath . $RefDocuments->strFileName);
                    }
                }
                RefDocument::where(['iDocumentType' => 1, "iRefId" => $Id])->delete();
            }
            $RefDocuments = RefDocument::where(['iDocumentType' => 2, "iRefId" => $Id])->get();
            if (!empty($RefDocuments->isEmpty)) {
                foreach ($RefDocuments as $RefDocument) {
                    if ($RefDocument->strFileName != "") {

                        $root = $_SERVER['DOCUMENT_ROOT'];
                        $destinationPath = $root . '/RefDocument/Image/';
                        if (file_exists($destinationPath . $RefDocument->strFileName)) {
                            unlink($destinationPath . $RefDocument->strFileName);
                        }
                    }
                    RefDocument::where(['iDocumentType' => 2, "iRefDocumentId" => $RefDocument->iRefDocumentId])->delete();
                }
            }
            $RefDocuments = RefDocument::where(['iDocumentType' => 3, "iRefId" => $Id])->get();
            if (!empty($RefDocuments)) {
                foreach ($RefDocuments as $RefDocument) {
                    if ($RefDocument->strFileName != "") {
                        $root = $_SERVER['DOCUMENT_ROOT'];
                        $destinationPath = $root . '/RefDocument/Video/';
                        if (file_exists($destinationPath . $RefDocument->strFileName)) {
                            unlink($destinationPath . $RefDocument->strFileName);
                        }
                    }
                    RefDocument::where(['iDocumentType' => 3, "iRefDocumentId" => $RefDocument->iRefDocumentId])->delete();
                }
            }
            Reference::where('iRefId', '=', $Id)->delete();

            return redirect()->route('reference.index')->with('Success', 'Reference Deleted Successfully!.');
        } else {
            return redirect()->route('home');
        }
    }
}
