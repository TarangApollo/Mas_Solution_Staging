<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Session;
use Illuminate\Support\Facades\Auth;

class CallAttendantResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->role_id == 3){
            return view('call_attendant.resetpassword');
        } else {
            return redirect()->route('home');
        }
    }

    public function changePassword(Request $request)
    {
        if(Auth::User()->role_id == 3){
            $session = Auth::user()->id;

            $user = User::where('id', '=', $session)->where(['status' => 1])->first();

            if (Hash::check($request->current_password, $user->password)) {
                $newpassword = $request->new_password;
                $confirmpassword = $request->new_confirm_password;

                if ($newpassword == $confirmpassword) {
                    $Student = DB::table('users')
                        ->where(['status' => 1, 'id' => $session])
                        ->update([
                            'password' => Hash::make($confirmpassword),
                        ]);
                    return back()->with('Success', 'Password Updated Successfully.');
                } else {
                    return back()->with('Error', 'Password and Confirm Password Does Not Match.');
                }
            } else {
                return back()->with('Error', 'Current Password Does Not Match.');
            }
        } else {
            return redirect()->route('home');
        }
    }
}
