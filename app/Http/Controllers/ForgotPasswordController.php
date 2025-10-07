<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WlUser;
use App\Models\CompanyMaster;
use App\Models\CallAttendent;
use Illuminate\Support\Facades\DB;
use App\Models\MessageMaster;
use App\Models\CompanyInfo;
use Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    //
    public function index(){
        return view('auth.forgotpassword');
    }

    public function forgotsubmit(Request $request){
        $SendEmailData = DB::table('sendemaildetails')
    			->where(['id' => 4])
            	->first();
        $user = User::where(['email' => $request->email,"status" => 1])->first();
        if(!empty($user)){
            $data = array(
                "resetPasswordDatetime" => date("Y-m-d H:i:s", strtotime('+1 hours'))
            );
            User::where(['email' => $request->email,"status" => 1])->update($data);
            $resetpasswordlink = route('forgotpassword.resetpassword',$user->id);
            if($user->role_id == 1){
                $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                $data = array(
                    "name" => $user->first_name ." ".$user->last_name,
                    "resetpasswordlink" => $resetpasswordlink,
                    "instaLink" => $companyInfo->instaLink,
                    "twitterLink" => $companyInfo->twitterlink,
                    "facebookLink" => $companyInfo->facebookLink,
                    "linkedinLink" => $companyInfo->linkedinlink,
                    "supportEmail" => $companyInfo->EmailId,
                    "ContactNo" => $companyInfo->ContactNo,
                    "Logo" => $companyInfo->strLogo
                );
                $msg = array(
                    'FromMail' => $SendEmailData->strFromMail,
                    'Title' => $SendEmailData->strTitle,
                    'ToEmail' => $request->email, 
                    'Subject' => $SendEmailData->strSubject
                );
            } else if($user->role_id == 2){
                $WlUser = WlUser::where(["iUserId" => $user->id,"iStatus" => 1,"isDelete" => 0])->first();
                $CompanyMaster = CompanyMaster::where(["iUserId" => $user->id,"iStatus" => 1,"isDelete" => 0])->first();
                if(!empty($WlUser)){
                    // Company Details and link
                    $CompanyMaster = CompanyMaster::where(["iCompanyId" => $WlUser->iCompanyId,"iStatus" => 1,"isDelete" => 0])->first();
                    if(!empty($CompanyMaster)){
                        $companyInfo = CompanyInfo::where('iCompanyId','=',$CompanyMaster->iCompanyId)->first();
                        if(!empty($companyInfo)){
                            $data = array(
                                "name" => $user->first_name ." ".$user->last_name,
                                "resetpasswordlink" => $resetpasswordlink,
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
                                'ToEmail' => $request->email, 
                                'Subject' => $SendEmailData->strSubject
                            );
                        } else {
                            $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                            $data = array(
                                "name" => $user->first_name ." ".$user->last_name,
                                "resetpasswordlink" => $resetpasswordlink,
                                "instaLink" => $companyInfo->instaLink,
                                "twitterLink" => $companyInfo->twitterlink,
                                "facebookLink" => $companyInfo->facebookLink,
                                "linkedinLink" => $companyInfo->linkedinlink,
                                "supportEmail" => $companyInfo->EmailId,
                                "ContactNo" => $companyInfo->ContactNo,
                                "Logo" => $companyInfo->strLogo
                            );
                            $msg = array(
                                'FromMail' => $SendEmailData->strFromMail,
                                'Title' => $SendEmailData->strTitle,
                                'ToEmail' => $request->email, 
                                'Subject' => $SendEmailData->strSubject
                            );
                        }
                    } else {
                        $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                        $data = array(
                            "name" => $user->first_name ." ".$user->last_name,
                            "resetpasswordlink" => $resetpasswordlink,
                            "instaLink" => $companyInfo->instaLink,
                            "twitterLink" => $companyInfo->twitterlink,
                            "facebookLink" => $companyInfo->facebookLink,
                            "linkedinLink" => $companyInfo->linkedinlink,
                            "supportEmail" => $companyInfo->EmailId,
                            "ContactNo" => $companyInfo->ContactNo,
                            "Logo" => $companyInfo->strLogo
                        );
                        $msg = array(
                            'FromMail' => $SendEmailData->strFromMail,
                            'Title' => $SendEmailData->strTitle,
                            'ToEmail' => $request->email, 
                            'Subject' => $SendEmailData->strSubject
                        );
                    }
                } else if(!empty($CompanyMaster)){
                    // Mas Solution and link
                    $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                    $data = array(
                        "name" => $user->first_name ." ".$user->last_name,
                        "resetpasswordlink" => $resetpasswordlink,
                        "instaLink" => $companyInfo->instaLink,
                        "twitterLink" => $companyInfo->twitterlink,
                        "facebookLink" => $companyInfo->facebookLink,
                        "linkedinLink" => $companyInfo->linkedinlink,
                        "supportEmail" => $companyInfo->EmailId,
                        "ContactNo" => $companyInfo->ContactNo,
                        "Logo" => $companyInfo->strLogo
                    );
                    $msg = array(
                        'FromMail' => $SendEmailData->strFromMail,
                        'Title' => $SendEmailData->strTitle,
                        'ToEmail' => $request->email, 
                        'Subject' => $SendEmailData->strSubject
                    );
                } else{
                    // Mas Solution and link
                    $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                    $data = array(
                        "name" => $user->first_name ." ".$user->last_name,
                        "resetpasswordlink" => $resetpasswordlink,
                        "instaLink" => $companyInfo->instaLink,
                        "twitterLink" => $companyInfo->twitterlink,
                        "facebookLink" => $companyInfo->facebookLink,
                        "linkedinLink" => $companyInfo->linkedinlink,
                        "supportEmail" => $companyInfo->EmailId,
                        "ContactNo" => $companyInfo->ContactNo,
                        "Logo" => $companyInfo->strLogo
                    );
                    $msg = array(
                        'FromMail' => $SendEmailData->strFromMail,
                        'Title' => $SendEmailData->strTitle,
                        'ToEmail' => $request->email, 
                        'Subject' => $SendEmailData->strSubject
                    );
                }
            } else {
                $CallAttendent = CallAttendent::where(["iUserId" => $user->id,"iStatus" => 1,"isDelete" => 0])->first();
                if(!empty($CallAttendent)){
                    if($CallAttendent->iExecutiveLevel == 1){
                        // Mas Solution and link
                        $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                        $data = array(
                            "name" => $user->first_name ." ".$user->last_name,
                            "resetpasswordlink" => $resetpasswordlink,
                            "instaLink" => $companyInfo->instaLink,
                            "twitterLink" => $companyInfo->twitterlink,
                            "facebookLink" => $companyInfo->facebookLink,
                            "linkedinLink" => $companyInfo->linkedinlink,
                            "supportEmail" => $companyInfo->EmailId,
                            "ContactNo" => $companyInfo->ContactNo,
                            "Logo" => $companyInfo->strLogo
                        );
                        $msg = array(
                            'FromMail' => $SendEmailData->strFromMail,
                            'Title' => $SendEmailData->strTitle,
                            'ToEmail' => $request->email, 
                            'Subject' => $SendEmailData->strSubject
                        );
                    } 
                    if($CallAttendent->iExecutiveLevel == 2){
                        $CompanyMaster = CompanyMaster::where(["iCompanyId" => $CallAttendent->iOEMCompany,"iStatus" => 1,"isDelete" => 0])->first();
                        if(!empty($CompanyMaster)){
                            // Company Details and link
                            $companyInfo = CompanyInfo::where('iCompanyId','=',$CompanyMaster->iCompanyId)->first();
                            if(!empty($companyInfo)){
                                $data = array(
                                    "name" => $user->first_name ." ".$user->last_name,
                                    "resetpasswordlink" => $resetpasswordlink,
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
                                    'ToEmail' => $request->email, 
                                    'Subject' => $SendEmailData->strSubject
                                );
                            } else {
                                // Mas Solution and link
                                $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                                $data = array(
                                    "name" => $user->first_name ." ".$user->last_name,
                                    "resetpasswordlink" => $resetpasswordlink,
                                    "instaLink" => $companyInfo->instaLink,
                                    "twitterLink" => $companyInfo->twitterlink,
                                    "facebookLink" => $companyInfo->facebookLink,
                                    "linkedinLink" => $companyInfo->linkedinlink,
                                    "supportEmail" => $companyInfo->EmailId,
                                    "ContactNo" => $companyInfo->ContactNo,
                                    "Logo" => $companyInfo->strLogo
                                );
                                $msg = array(
                                    'FromMail' => $SendEmailData->strFromMail,
                                    'Title' => $SendEmailData->strTitle,
                                    'ToEmail' => $request->email, 
                                    'Subject' => $SendEmailData->strSubject
                                );
                            }
                        } else {
                            // Mas Solution and link
                            $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                            $data = array(
                                "name" => $user->first_name ." ".$user->last_name,
                                "resetpasswordlink" => $resetpasswordlink,
                                "instaLink" => $companyInfo->instaLink,
                                "twitterLink" => $companyInfo->twitterlink,
                                "facebookLink" => $companyInfo->facebookLink,
                                "linkedinLink" => $companyInfo->linkedinlink,
                                "supportEmail" => $companyInfo->EmailId,
                                "ContactNo" => $companyInfo->ContactNo,
                                "Logo" => $companyInfo->strLogo
                            );
                            $msg = array(
                                'FromMail' => $SendEmailData->strFromMail,
                                'Title' => $SendEmailData->strTitle,
                                'ToEmail' => $request->email, 
                                'Subject' => $SendEmailData->strSubject
                            );
                        }
                    }
                } else {
                    // Mas Solution and link
                    $companyInfo = CompanyInfo::where('iCompanyInfoId','=','1')->first();
                    $data = array(
                        "name" => $user->first_name ." ".$user->last_name,
                        "resetpasswordlink" => $resetpasswordlink,
                        "instaLink" => $companyInfo->instaLink,
                        "twitterLink" => $companyInfo->twitterlink,
                        "facebookLink" => $companyInfo->facebookLink,
                        "linkedinLink" => $companyInfo->linkedinlink,
                        "supportEmail" => $companyInfo->EmailId,
                        "ContactNo" => $companyInfo->ContactNo,
                        "Logo" => $companyInfo->strLogo
                    );
                    $msg = array(
                        'FromMail' => $SendEmailData->strFromMail,
                        'Title' => $SendEmailData->strTitle,
                        'ToEmail' => $request->email, 
                        'Subject' => $SendEmailData->strSubject
                    );
                }
            }
            $root = $_SERVER['DOCUMENT_ROOT'];
            //$file = file_get_contents("https://getdemo.in/mas_solutions/mailers/welcome-company.html", "r");
            $file = file_get_contents($root."/mailers/forgot-pw.html", "r");
        
            if($data['Logo'] != ""){
                $file = str_replace("#strLogo#",env('APP_URL').'/WlLogo/'.$data['Logo'] ,$file);
            } else {
                $file = str_replace("#strLogo#","https://massolutions.thenexus.co.in/global/assets/images/logo.png",$file);
            }
        
            /*if($user['instaLink'] != ""){
                $file = str_replace("#instaLinkDisplay#", '' ,$file);
                $file = str_replace("#instaLink#",$user['instaLink'] ,$file);
            } else {
                $file = str_replace("#instaLinkDisplay#", 'none' ,$file);
            }
        
            if($user['twitterLink'] != ""){
                $file = str_replace("#twitterLinkDisplay#", '' ,$file);
                $file = str_replace("#twitterLink#",$user['twitterLink'] ,$file);
            } else {
                $file = str_replace("#twitterLinkDisplay#", 'none' ,$file);
            }*/
            $instaLink = "";
            if(isset($data['instaLink']) && $data['instaLink'] != ""){
                $instaLink = $data['instaLink'];
            } else {
                $instaLink = "https://www.instagram.com";
            }
        
            $file = str_replace('#instaLinkDisplay#', '', $file);
            $file = str_replace('#instaLink#', $instaLink, $file);
            
            $twitterLink = "";
            if ($data['twitterLink'] != '') {
                $twitterLink = $data['twitterLink'];
            } else {
                $twitterLink = "https://x.com";
            }
            $file = str_replace('#twitterLinkDisplay#', '', $file);
            $file = str_replace('#twitterLink#', $twitterLink, $file);
                
            
              
        
            $file = str_replace("#CustomerName#",$data['name'] ,$file);
            $file = str_replace("#resetpasswordLink#",$data['resetpasswordlink'],$file);
            $file = str_replace("#ContactNo#",$data['ContactNo'] ,$file);
            if($data['supportEmail'] != ""){
                $file = str_replace("#supportEmail#",$data['supportEmail'] ,$file);
            } else {
                $file = str_replace("#supportEmail#","support@masolutions.co.uk" ,$file);
            }
            
            /*$to = "variyatradingcompany@gmail.com";
            $subject = $msg['Subject'];
            $headers = "From: " . $msg['FromMail'] . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            //$headers .= "Cc: $cc\r\n";
            $headers .= "Content-type: text/html\r\n";
    
            $mail = mail($to, $subject, $file, $headers);*/
            
            $mail = Mail::send('emails.forgotpassword',['user'=>$data],function($message) use ($msg) {
                $message->from($msg['FromMail'],$msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
                
            });
            
            return redirect()->route('login')->with('success','Success! Reset password link sent successfully. The reset link will expire in 1 hour.');
        } else {
            return redirect()->route('forgotpassword.index')->with('error','User Not Found.!');
        }
    }

    public function resetpassword(Request $request,$id){
        $users = User::where(['id' => $id,"status" => 1])->first();
        $resetPasswordDatetime = strtotime($users->resetPasswordDatetime);
        $expireDate = strtotime(date('Y-m-d H:i:s'));
        
        $flag = "";
        if($resetPasswordDatetime < $expireDate){
            $flag = 0;  
        } else {
            $flag = 1;
        }
        return view('auth.resetpassword',compact('users','flag'));
    }

    public function resetpasswordsubmit(Request $request){
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required',
            'cpassword' => 'required'
        ]);

        if($request->password === $request->cpassword){
            $userData = array(
                "password" => Hash::make($request->password)
            );
            $users = User::where(['id' => $request->id])->update($userData);
            return redirect()->route('login')->with('success','New Password set successfully.');
        } else {
            return redirect()->back()->with('error','Password and Confirm Password Not Match.!');
        }
    }
}
