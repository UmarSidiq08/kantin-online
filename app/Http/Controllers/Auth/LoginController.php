<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    protected function redirectTo()
    {
        if (Auth::user()->role === 'admin') {
            return '/admin/dashboard';
        } elseif (Auth::user()->role === 'user') {
            return '/user/dashboard';
        }

        return '/';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
