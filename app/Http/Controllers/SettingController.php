<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyInfo;
use App\Models\MessageMaster;
use App\Models\infoTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $CompanyInfo = CompanyInfo::where('iCompanyId', Session::get('CompanyId'))
                ->orderBy('iCompanyInfoId', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1])->first();

            $openmessage = MessageMaster::where(['iStatusId' => '0', 'isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => Session::get('CompanyId')])->first();
            $closemessage = MessageMaster::where(['iStatusId' => '1', 'isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => Session::get('CompanyId')])->first();
            $autoclosemessage = MessageMaster::where(['iStatusId' => '2', 'isDelete' => 0, 'iStatus' => 1, 'iCompanyId' => Session::get('CompanyId')])->first();

            return view('wladmin.setting.index', compact('CompanyInfo', 'openmessage', 'closemessage', 'autoclosemessage'));
        } else {
            return redirect()->route('home');
        }
    }


    public function generalSetting(Request $request)
    {
        // dd($request);
        if (Auth::User()->role_id == 2) {
            //try {
            $companyInfoDetail = CompanyInfo::where("iCompanyId", Session::get('CompanyId'))->first();
            
            if ($request->hasFile('photo')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('photo');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/WlLogo/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
                $oldImg = "";
                if(isset($companyInfoDetail->strLogo) && $companyInfoDetail->strLogo != ""){
                    $oldImg = $companyInfoDetail->strLogo;
                }

                if ($oldImg != null || $oldImg != "") {
                    if (file_exists($destinationpath . $oldImg)) {
                        unlink($destinationpath . $oldImg);
                    }
                }
                // if($img != ""){
                //     CompanyInfo::where("iCompanyInfoId", '=',   $iCompanyInfoId)->update([]);
                // }
            } else {
                $img = $companyInfoDetail->strLogo;
            }
            
            $data = array(
                'ContactNo' => $request->ContactNo,
                'EmailId' => $request->EmailId,
                'headerColor' => $request->headerColor,
                'menuColor' => $request->menucolor,
                'menubgColor' => $request->iconcolor,
                'iCompanyId' => Session::get('CompanyId'),
                'strCompanyName' => Session::get('CompanyName'),
                'strLogo' => $img
            );
            if (!$companyInfoDetail) {
                $iCompanyInfoId = CompanyInfo::create($data);
                $action = "Insert";
            } else {
                $iCompanyInfoId = $companyInfoDetail->iCompanyInfoId;
                CompanyInfo::where("iCompanyInfoId", '=', $iCompanyInfoId)->update($data);
                $action = "Update";
            }
            
            $this->changeCssColor($request);

            

            $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            $userdata = \App\Models\User::whereId($session)->first();

            $infoArr = array(
                'tableName'    => "companyinfo",
                'tableAutoId'    => $iCompanyInfoId,
                'tableMainField'  => "General Information",
                'action'     => $action,
                'strEntryDate' => date("Y-m-d H:i:s"),
                'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
            );
            $Info = infoTable::create($infoArr);

            DB::commit();
            return redirect()->route('setting.index')->with('Success', 'Company General Data Updated.');
            echo 1;
            // } catch (\Throwable $th) {
            //     DB::rollBack();
            //     return redirect()->route('setting.index')->with('Error', 'invalid Request.');
            //     echo 0;
            // }
        } else {
            return redirect()->route('home');
        }
    }

    public function socialSetting(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                $companyInfoDetail = CompanyInfo::where("iCompanyId", Session::get('CompanyId'))->first();

                $data = array(
                    'facebookLink' => $request->facebookLink,
                    'instaLink' => $request->instaLink,
                    'twitterlink' => $request->twitterlink,
                    'linkedinlink' => $request->linkedinlink,
                    'iCompanyId' => Session::get('CompanyId'),
                    'strCompanyName' => Session::get('CompanyName')
                );

                if (!$companyInfoDetail) {
                    $iCompanyInfoId = CompanyInfo::create($data);
                    $action = "Insert";
                } else {
                    $iCompanyInfoId = $companyInfoDetail->iCompanyInfoId;
                    CompanyInfo::where("iCompanyInfoId", '=', $iCompanyInfoId)->update($data);
                    $action = "Update";
                }

                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $userdata = \App\Models\User::whereId($session)->first();

                $infoArr = array(
                    'tableName'    => "companyinfo",
                    'tableAutoId'    => $iCompanyInfoId,
                    'tableMainField'  => "Social setting",
                    'action'     => $action,
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Company Social media Data Updated.');
                DB::commit();
                // return redirect()->route('setting.index');
                echo 1;
            } catch (\Throwable $th) {
                DB::rollBack();
                return redirect()->route('setting.index')->with('Error', 'invalid Request.');
                echo 0;
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function mailSetting(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            try {
                foreach ($request->iStatusId as $iStaus) {

                    $editor = "msg_" . $iStaus;
                    $tocompany = "toCompany_" . $iStaus;
                    $toExecutive = "toExecutive_" . $iStaus;
                    $tocustomer = "toCustomer_" . $iStaus;
                    $chkMessage = MessageMaster::where(['iCompanyId' => Session::get('CompanyId'), 'iStatusId' => $iStaus])->first();

                    $data = array(
                        "iStatusId" => $iStaus,
                        "strMessage" => trim($request->$editor) ?? '',
                        "iCompanyId" => Session::get('CompanyId'),
                        "toCustomer" => $request->$tocustomer ?? 0,
                        "toCompany" => $request->$tocompany ?? 0,
                        "toExecutive" => $request->$toExecutive ?? 0,

                    );


                    if ($chkMessage) {
                        $messageId = $chkMessage->iMessageId;
                        MessageMaster::where("iMessageId", '=', $messageId)->update($data);
                        $action = "Update";
                    } else {
                        $messageId = MessageMaster::create($data);
                        $action = "Insert";
                    }


                    $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                    $userdata = \App\Models\User::whereId($session)->first();
                    $infoArr = array(
                        'tableName'    => "companyinfo",
                        'tableAutoId'    => $messageId,
                        'tableMainField'  => "Mail setting",
                        'action'     => $action,
                        'strEntryDate' => date("Y-m-d H:i:s"),
                        'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                    );
                    $Info = infoTable::create($infoArr);
                }
                Session::flash('Success', 'Company Mail Setting Updated.');
                DB::commit();

                return redirect()->route('setting.index');
                echo 1;
            } catch (\Throwable $th) {
                DB::rollBack();
                //echo $th->getMessage();
                return redirect()->route('setting.index')->with('Error', 'invalid Request.');
                echo 0;
            }
        } else {
            return redirect()->route('home');
        }
    }


    public function changeCssColor($request)
    {
        // dd($request);
        $headerColor = "";
        $menuColor = "";
        $menubgColor = "";
        if (isset($request->headerColor) && $request->headerColor != "") {
            $headerColor = $request->headerColor;
        } else {
            $headerColor = "#e9593e";
        }

        if (isset($request->menucolor) && $request->menucolor != "") {
            $menuColor = $request->menucolor;
        } else {
            $menuColor = "#e9593e";
        }

        if (isset($request->iconcolor) && $request->iconcolor != "") {
            $menubgColor = $request->iconcolor;
        } else {
            $menubgColor = "#e9593e";
        }

        $root = $_SERVER['DOCUMENT_ROOT'];
        $file = file_get_contents($root . "/global/assets/css/style-wl.css", "r");
        $filefd = file_get_contents($root . "/global/assets/css/style-fd.css", "r");

        $file = str_replace("#header", $headerColor . " !important", $file);
        $file = str_replace("#menuhover", $menuColor . " !important", $file);
        $file = str_replace("#MenuBackground", $menubgColor . " !important", $file);
        $filefd = str_replace("#header", $headerColor, $filefd);
        $CompanyName =  Session::get('CompanyName');
        $destinationpath = $root . '/global/assets/css/';
        $string = str_replace(' ', '_', $CompanyName);
        $stringfd = str_replace(' ', '_', $CompanyName . "_fd");
        $CompanyfileName = preg_replace('/[^A-Za-z0-9\-]/', '', $string) . '.css';
        $CompanyfileNameFd = preg_replace('/[^A-Za-z0-9\-]/', '', $stringfd) . '.css';
        if (file_exists($destinationpath . $CompanyfileName)) {
            unlink($destinationpath . $CompanyfileName);
        }
        if (!file_exists($destinationpath)) {
            mkdir($destinationpath, 0755, true);
        }

        if (file_exists($destinationpath . $CompanyfileNameFd)) {
            unlink($destinationpath . $CompanyfileNameFd);
        }

        $fp = fopen($destinationpath . $CompanyfileName, "wb");
        $fpfd = fopen($destinationpath . $CompanyfileNameFd, "wb");
        fwrite($fp, $file);
        fwrite($fpfd, $filefd);
        fclose($fpfd);
        fclose($fp);
    }
}
