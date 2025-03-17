<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        
        $validated = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|min:3',
            'captcha'  => ['required', 'captcha'],
        ], [
            'login.required'    => 'Please enter your email or username.',
            'password.required' => 'Please enter your password.',
            'password.min'      => 'Password must be at least 3 characters long.',
            'captcha.required'  => 'Please enter the CAPTCHA.',
            'captcha.captcha'   => 'Incorrect CAPTCHA! Try again.',
        ]);
        
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        $user = User::where($fieldType, $request->login)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'User not found.'])->withInput();
        }

        if ($user->user_type !== 'admin') {
            return back()->withErrors(['login' => 'You are not authorized to log in.'])->withInput();
        }

        if (Auth::attempt([$fieldType => $request->login, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard')->with('success', 'Login successful');
        } else {
            return back()->withErrors(['login' => 'Invalid credentials.'])->withInput();
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function test()
    {
        return view('admin.login');
    }
    
    public function profile(Request $request){
        return view('user.user_profile');
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