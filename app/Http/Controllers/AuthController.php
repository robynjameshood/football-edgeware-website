<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private const USERS = [
        'r_blackham@outlook.com' => 'trojanhorse111!!!',
        'kellymoss@hotmail.co.uk' => 'trojanhorse111!!!',
    ];

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (isset(self::USERS[$email]) && self::USERS[$email] === $password) {
            Session::put('is_admin', true);
            Session::put('admin_email', $email);
            return redirect()->intended(route('results'));
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        Session::forget(['is_admin', 'admin_email']);
        return redirect()->route('login');
    }
}
