<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class HomeController extends Controller
{
        public function home()
    {
        return view('home.home');
    }
}
