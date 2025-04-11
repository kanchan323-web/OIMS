<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use App\Models\RigUser;


class LoginController extends Controller
{
    public function oims_index()
    {
        return view('home');
    }

    public function index()
    {
        return view('user.login');
    }

    public function profile(Request $request)
    {
        $moduleName = "User Profile";
        $rig_id = Auth::user()->rig_id;
        $RigUser =  RigUser::where('id', $rig_id)->first();

        return view('user.user_profile', compact('moduleName', 'RigUser'));
    }

    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:3',
            // 'captcha'  => ['required', 'captcha'],
        ], [
            'login.required'    => 'Please enter your email or username.',
            'password.required' => 'Please enter your password.',
            'password.min'      => 'Password must be at least 3 characters long.',
            // 'captcha.required'  => 'Please enter the CAPTCHA.',
            // 'captcha.captcha'   => 'Incorrect CAPTCHA! Try again.',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('user_name', $request->login)
            ->first();

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $request->password])) {

            // $this->notifyAdmins("User '{$user->user_name}' has logged in.");

            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

    public function logout()
    {
        $user = Auth::user();
        Auth::logout();

        // if ($user) {
        //     $this->notifyAdmins("User '{$user->user_name}' has logged out.");
        // }

        return redirect()->route('user.login');
    }

    public function forgotpassword()
    {
        return view('forgotpassword');
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


    public function mapuserlist(Request $request)
    {
        $moduleName = "Map User List";
        $rig_id = Auth::user()->rig_id;
        $data = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->get();
        return view('user.map_user_list', compact('data', 'moduleName'));
    }


    public function mapuserstockview(Request $request)
    {
        return view('user.map_user_add_stock_view');
    }



    public function mapuserdataget(Request $request)
    {
        $moduleName = "Map User Stock";
        $id = $request->id;
        if (!$id) {
            return redirect()->back()->with('error', 'User ID is required');
        }
        $tally = User::join('stocks', 'users.id', '=', 'stocks.user_id')
            ->select('users.*', 'stocks.*')
            ->where('users.id', $id)
            ->get();

        $data = User::where('id', $id)->first();


        if ($tally->isEmpty()) {
            return redirect()->back()->with('error', 'No Data Found for the Selected User');
        }

        return view('user.map_user_add_stock_view', compact('data', 'tally', 'moduleName'));
    }



    public function mapspecificuserdata(Request $request)
    {

        $id = $request->data;

        $data = User::join('stocks', 'users.id', '=', 'stocks.user_id')
            ->select('users.*', 'stocks.*')
            ->where('users.id', $id)
            ->get();

        return response()->json([
            "data" => $data
        ]);
    }

    public function showResetForm($user_id, $token)
    {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Invalid token or user!');
        }

        return view('new-password', [
            'token' => $token,
            'email' => $user->email,
            'user_id' => $user_id
        ]);
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Invalid token!']);
        }

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // $this->notifyAdmins("User '{$user->user_name}' has changed their password.");

        if ($user->user_type === 'admin') {
            return redirect()->route('admin.login')->with('success', 'Password has been reset successfully!');
        } else {
            return redirect()->route('user.login')->with('success', 'Password has been reset successfully!');
        }
    }


    // private function notifyAdmins($message)
    // {
    //     $admins = User::where('user_type', 'admin')->get();

    //     foreach ($admins as $admin) {
    //         Notification::create([
    //             'type'            => NewRequestNotification::class,
    //             'notifiable_type' => User::class,
    //             'notifiable_id'   => $admin->id,
    //             'user_id'         => $admin->id,
    //             'data'            => json_encode(['message' => $message, 'url' => null]),
    //             'created_at'      => now(),
    //             'updated_at'      => now(),
    //         ]);
    //     }
    // }
}
