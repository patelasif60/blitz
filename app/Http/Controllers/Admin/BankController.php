<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\XenditController;
use App\Models\AvailableBank;
use App\Models\SystemActivity;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create available banks|edit available banks|delete available banks|publish available banks|unpublish available banks', ['only' => ['index']]);
        $this->middleware('permission:create available banks', ['only' => ['sync']]);
        $this->middleware('permission:edit available banks', ['only' => ['edit']]);
        $this->middleware('permission:delete available banks', ['only' => ['destroy']]);
    }

    public function index(){
        /**begin: system log**/
        AvailableBank::bootSystemView(new AvailableBank());
        /**end:  system log**/
        return view('admin/banks/index', ['banks' => AvailableBank::all()->sortDesc()]);
    }

    public function sync(){
        $xendit = new XenditController();
        $data = $xendit->getAvailableBanks();
        foreach ($data as $raw) {
            AvailableBank::createOrUpdateBank($raw);
        }
        return response()->json(array('success' => true,'data'=>$data));
    }

    public function uploadLogo(Request $request)
    {
        $bank = AvailableBank::find($request->id);
        if ($bank) {
            if ($bank->logo){
                try {
                    unlink(public_path($bank->logo));
                }catch (\Exception $exception){}
            }

            $fileName = time() . '.' . $request->logo->extension();

            $request->logo->move(public_path('uploads/bank_logo'), $fileName);

            $bank->logo =  'uploads/bank_logo/'.$fileName;
            $bank->save();

            /**begin: system log**/
            $bank->bootSystemView(new AvailableBank(), 'AvailableBank', SystemActivity::EDITVIEW, $bank->id);
            /**end: system log**/
            return redirect('admin/banks')->with('success', 'Logo is uploaded successful');
        }
        return redirect('admin/banks')->with('error','This bank is not available!');
    }

}
