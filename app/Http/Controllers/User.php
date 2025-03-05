<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Illuminate\Support\Str;
use Mail;
use App\Mail\SendMail;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class User extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);

       // var_dump($data);
      //  die;
       $captcha_token = array_pop($credentials);
      // print_r($credentials);

      // print_r($data);
      // die;


        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
           // return view('dashboard');
        }else{
            return view('user.login')->with('error', 'Invalid credentials');
        }
    }

    public function dashboard(){
        if (Auth::check()) {
            return view('user.dashboard');
        }else{
            return redirect()->route('login');
        }

            //return redirect('dashboard')->with('success', 'Login successful! Please log in.');

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
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
