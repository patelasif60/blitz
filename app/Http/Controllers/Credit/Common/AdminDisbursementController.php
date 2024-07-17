<?php

namespace App\Http\Controllers\Credit\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDisbursementController extends Controller
{
    //
    function index()
    {
        return view('admin/limit/disbursement');
    }
}
