<?php

namespace App\Http\Controllers\Credit\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreditApplicationController extends Controller
{
    //
    function index()
    {
        
        return view('admin/limit/credit_application');
    }
}
