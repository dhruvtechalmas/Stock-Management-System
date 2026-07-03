<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardConroller extends Controller
{
    public function index()
    {
        return view('stocks.index');
    }
}
