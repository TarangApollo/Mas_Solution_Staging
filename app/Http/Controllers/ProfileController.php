<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallAttendent;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->role_id == 1){
            return view('admin.profile');
        } else {
            return redirect()->route('home'); 
        }
    }

    public function updateProfile(Request $request)
    {
        if(Auth::User()->role_id == 1){
            $img = "";
            if ($request->hasFile('photo')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('photo');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/ProfilePhoto/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
                $oldImg = $request->input('hiddenPhoto') ? $request->input('hiddenPhoto') : null;

                if ($oldImg != null || $oldImg != "") {
                    if (file_exists($destinationpath . $oldImg)) {
                        unlink($destinationpath . $oldImg);
                    }
                }
            } elseif ($request->input('hiddenPhoto')) {
                $oldImg = $request->input('hiddenPhoto');
                $img = $oldImg;
            } else {
                // $root = $_SERVER['DOCUMENT_ROOT'];
                // $img = $root . '/images/noimage.jpg';
                //   $img = null;
            }
            // DB::table('users')->where(['status' => 1, 'id' => auth()->user()->id])->delete();

            // $delete = DB::table('users')->where(['status' => 1, 'id' => auth()->user()->id])->first();

            // $root = $_SERVER['DOCUMENT_ROOT'];
            // $destinationpath = $root . '/ProfilePhoto/';

            // if ($delete->photo != "") {
            //     unlink($destinationpath . $delete->photo);
            // }

            $parts = explode(' ', $request->first_name);
            $firstname = array_shift($parts);
            $lastname = array_pop($parts);

            $Student = DB::table('users')
                ->where(['status' => 1, 'id' => auth()->user()->id])
                // ->where(auth()->user()->id)
                ->update([
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'photo' => $img,
                    'mobile_number' => $request->mobile_number,
                    'email' => $request->email,
                ]);
            // dd($Student);
            return redirect()->back()->with('Success', 'Profile Updated Successfully.');
        } else {
            return redirect()->route('home'); 
        }
    }
}
