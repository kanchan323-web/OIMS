<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index()
    {
        return view('user.login');
    }

    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:3',
            'captcha'  => ['required', 'captcha'],
        ], [
            'login.required'    => 'Please enter your email or username.',
            'password.required' => 'Please enter your password.',
            'password.min'      => 'Password must be at least 3 characters long.',
            'captcha.required'  => 'Please enter the CAPTCHA.',
            'captcha.captcha'   => 'Incorrect CAPTCHA! Try again.',
        ]);

        // Check if user exists by email or username
        $user = User::where('email', $request->login)
            ->orWhere('user_name', $request->login)
            ->first();

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login');
    }

    public function forgotpassword()
    {
        return view('user.forgotpassword');
    }

    public function submitPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            $token = Str::random(64);

            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            DB::table('password_reset_tokens')->insert([
                'email'      => $user->email,
                'token'      => $token,
                'created_at' => now(),
            ]);

            Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

            return back()->with('success', 'A password reset link has been sent to your email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }
}
