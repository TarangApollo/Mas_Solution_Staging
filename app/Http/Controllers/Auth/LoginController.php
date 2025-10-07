<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\MessageMaster;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    protected function authenticated(Request $request, $user)
    {
        // Check if user exists in multiplecompanyrole table
        $hasMultipleRoles = DB::table('multiplecompanyrole')
            ->where('userid', $user->id)
            ->exists();
        
        if ($hasMultipleRoles) {
            // Update user role to 2
            DB::table('users')
                ->where('id', $user->id)
                ->where('isCanSwitchProfile',1)
                ->update(['role_id' => 2]);
            
            // Refresh user data
            $user->refresh();
            $hasMultipleRoles = DB::table('multiplecompanyrole')
                ->where('userid', $user->id)
                ->first();
            // $request->session()->put('CompanyId', $companyUser->iCompanyId);
            //dd($hasMultipleRoles);
        }
    
        return redirect()->intended($this->redirectPath());
    }
    
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ],
        [
            'captcha.captcha' => 'Invalid captcha code.'
        ]
        );
        $User = User::where(['email' => $request->email])->first(); 
        //dd($User);
        if(!empty($User)){
            //dd($User);
            if (auth()->attempt(array('email' => $request->email, 'password' => $request->password,'status' => 1))) {
            
            } else {
                $MessageMaster = MessageMaster::where('iMessageId','=','1')->first();
                return redirect()->route('login',compact('MessageMaster'))
                ->with('error', 'Error! Incorrect email or password.');   
            }
        } else {
            $MessageMaster = MessageMaster::where('iMessageId','=','1')->first();
            back()->withErrors([
                'errors' => $MessageMaster->strMessage,
            ])->onlyInput('email');
            //return redirect()->route('login')
            //    ->with('errors', $MessageMaster->strMessage);
        }
        // if (auth()->attempt(array('email' => $request->email, 'password' => $request->password))) {
            
        // } else {
            
        // }
    }
}
