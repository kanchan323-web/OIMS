<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Mail;
use App\Mail\SendMail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index(){
        return view('user.login');
    }

    public function authenticate(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

    /*   if ($validated->fails()) {
        return redirect()->route('user.login')->withErrors($validated)->withInput();
        }else{ */
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('user.dashboard')->with('Login successfully');
            }else{
                return redirect()->route('user.login')->with('error','Either email or password is incorrect');
            }
      //  }

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('user.login');
    }

    public function forgotpassword(){
        return view('user.forgotpassword');
    }

    public function submitpassword(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email|exists:users',
        // 'g-recaptcha-response' => 'required|captcha',
        ]);

        $token = Str::random(64);
        //DB::table('users')->where(email,$request->email->delete);


        //echo $token;
    /*  $mailData = [
            'title' => 'Mail from gmail.com',
            'body' => 'This is for testing email using smtp.'
        ];
        Mail::to('kanchansilvertouch@gmail.com')->send(new SendMail($mailData));
    // dd("Email is sent successfully.");
    */
    Mail::to('kanchansilvertouch@gmail.com')->send(new SendMail([
        'title' => 'The Title',
        'body' => 'The Body',
    ]));


    // var_dump($credentials);
    }
    }

?>
