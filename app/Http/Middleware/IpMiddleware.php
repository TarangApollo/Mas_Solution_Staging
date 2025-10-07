<?php

namespace App\Http\Middleware;
//namespace App;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MessageMaster;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        $sessionId = $request->session()->all();
        $loginId = $sessionId['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];
        $users = User::where('id', '=', $loginId)->first();
        //dd($users);
        $rolesId = \DB::select("SELECT `role_id` FROM `users` WHERE `id`='" . $loginId . "'");
        $roles = $rolesId[0];

        if ($users->status == 0) {
            Auth::logout();
            Session::forget('user');
            $MessageMaster = MessageMaster::where('iMessageId','=','1')->first();
            return redirect()->route('login')->withErrors($MessageMaster->strMessage);
        } else {
            return $next($request);
        }
    }
}
