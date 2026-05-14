<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function index()
    {
        return view('login.index'); 
    }

    public function login(Request $request)
    {
    
        return redirect('/home')->with('mensaje', 'Login exitoso');
    }


    public function logout(Request $request)
    {
        return redirect('/');
    }
    
}
