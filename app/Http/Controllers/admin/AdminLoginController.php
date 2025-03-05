<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index(){
        return view('admin.login');
    }

    public function authenticate(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

    /*   if ($validated->fails()) {
        return redirect()->route('admin.login')->withErrors($validated)->withInput();
        }else{ */
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
                if(Auth::guard('admin')->user()->user_type != "admin"){
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','You are not authorized a person');
                }
                return redirect()->route('admin.dashboard')->with('Login successfully');
            }else{
                return redirect()->route('admin.login')->with('error','Either email or password is incorrect');
            }
      //  }

    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

 /*    public function register(){
        return view('register');
    }

    public function registerSubmit(Request $request){
        $validated = $request->validate([
            'user_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

      if ($validated->fails()) {
             return redirect()->route('admin.register')->withErrors($validated)->withInput();
        }else{
            $user = new User();
            $user->name = $request->user_name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->role = 'user';
            $user->save();
            return redirect()->route('admin.login')->with('success','You have registed successfully.');
       // }

    }
       */
}
