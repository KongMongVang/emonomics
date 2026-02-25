<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->is_suspended) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account is suspended. Please contact support.']);
        }
    }
}