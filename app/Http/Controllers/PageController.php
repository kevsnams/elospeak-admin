<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('page.login');
        }

        return view('page.index');
    }

    public function auth(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors([
                'message' => 'Incorrect Username or Password'
            ]);
        }

        return redirect(route('pages.index'));
    }

    public function unauth()
    {
        Auth::logout();
        return redirect(route('pages.index'));
    }
}
