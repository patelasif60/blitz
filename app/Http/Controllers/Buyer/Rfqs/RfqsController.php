<?php

namespace App\Http\Controllers\Buyer\Rfqs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RfqStatus;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RfqsController extends Controller
{
    //check parmission
    public function __construct()
    {
        $this->middleware('permission:publish buyer rfqs',['only' =>['rfqs']]);
    }
    // list all rfq with pagignation
    public function rfqs(){
        $rfqStatus =RfqStatus::all();
        $companyData = Company::where('id', Auth::user()->default_company)->first(['companies.registrantion_NIB','companies.npwp']);
        $isOwner = User::checkCompanyOwner();
        return View('buyer.rfqs.index',compact('rfqStatus','companyData','isOwner'));        
    }
}