<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function  home()
    {
        return redirect()->route('admin.dashboard');
    }

    public function test()
    {
        
    }
}
