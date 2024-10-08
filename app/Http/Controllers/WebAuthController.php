<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ForgotPasswordMail;
use Hash;
use Auth;
use Mail;
use Str;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class WebAuthController extends Controller
{
    public function forgotpassword()
    {
        return view('Auth.forgotpassword');
    }

    public function PostForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Retrieve the user by email
        $user = User::where('email', $request->email)->first();

        // Generate a new remember_token
        $user->remember_token = Str::random(60);
        $user->save();

        // Send the email with the reset link
        Mail::to($user->email)->send(new ForgotPasswordMail($user));

        return redirect()->back()->with('success', 'Password reset link has been sent to your email.');
    }

    public function loginuser()
    {
        return view("Auth.login");
    }

    public function profile()
    {
        return view("Student.studentProfile");
    }

    public function login()
    {
        if (!empty(Auth::check())) {
            if (Auth::user()->user_type == 1) {
                return redirect('admin/dashboard');
            } elseif (Auth::user()->user_type == 2) {
                return redirect('teacher/dashboard');
            } elseif (Auth::user()->user_type == 3) {
                return redirect('student/dashboard');
            } elseif (Auth::user()->user_type == 4) {
                return redirect('manager/dashboard');
            }
        }

        return view('Auth.login');
    }

    public function Authlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:20'
        ]);

        $remember = !empty($request->remember);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('loginuser')->with('warning', 'You need to verify your email address before logging in.');
            }

            if (Auth::user()->user_type == 1) {
                return redirect('admin/dashboard');
            } elseif (Auth::user()->user_type == 3) {
                return redirect('student/dashboard');
            }
        } else {
            return redirect()->back()->with('fail', 'Incorrect Email or Password');
        }
    }

    public function registration()
    {
        return view("Auth.register");
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|max:20|confirmed',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->user_type = 3; 
            $user->save();

            // Safeguard against sending verification if the user wasn't created properly
            if ($user) {
                $user->sendEmailVerificationNotification(); // Send verification email
            }

            return redirect()->route('loginuser')->with('success', 'Registration successful. Please check your email to verify your account.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);

        if ($user) {
            $data['user'] = $user;
            $data['token'] = $remember_token;
            return view('Auth.resetpass', $data);
        } else {
            return redirect()->route('forgotpassword')->with('error', 'Invalid or expired reset token.');
        }
    }

    public function PostReset($token, Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:5|max:20|confirmed',
        ]);

        $user = User::getTokenSingle($token);

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();

            return redirect(url('/'))->with('success', 'Password successfully reset');
        } else {
            return redirect()->back()->with('error', 'Invalid token or user not found.');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:5|max:20|confirmed',
        ]);

        $user = Auth::user();

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }
}
