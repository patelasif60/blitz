<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Models\BuyerBanks;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Buyer\BuyerBanks\AddBuyerBanksRequest;
use Auth;
use Illuminate\Support\Facades\App;

class BuyerBanksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\AddBuyerBanksRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBuyerBanksRequest $request)
    {
        if($request->ajax()){

            $userId = Auth::user()->id;

            if (!empty($userId)) {

                //Reset existing primary banks
                $resetPrimary = $request->isPrimary > BuyerBanks::NOTPRIMARY ? $this->resetPrimaryBank($userId) : true;

                if ($resetPrimary) {

                    $buyerBanks = BuyerBanks::Create([
                        'bank_id'               =>  $request->bankName,
                        'user_id'               =>  $userId,
                        'company_id'            =>  Auth::user()->default_company,
                        'account_holder_name'   =>  $request->bankAccountHolderName,
                        'account_number'        =>  $request->bankAccountNumber,
                        'description'           =>  $request->bankDescription,
                        'created_by'            =>  $userId,
                        'is_primary'            =>  empty($request->isPrimary) ? BuyerBanks::NOTPRIMARY : $request->isPrimary,
                    ]);

                    if (!empty($buyerBanks)) {
                        buyerNotificationInsert(Auth::user()->id, 'Buyer Bank Created', 'buyer_bank_created_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                        broadcast(new BuyerNotificationEvent());
                        return response()->json(['response' => 'success', 'message'=> 'Bank added successfully', 'success' => 'true']);

                    }

                }

            }

            return response()->json(['response' => 'error', 'message'=> 'Something went wrong', 'success' => 'false']);

        }

        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if ($request->ajax()) {

            $buyerBank = BuyerBanks::where('id',$id)->first();

            $data = [
                'id'                    => $buyerBank->id,
                'bank_id'               => $buyerBank->bank_id,
                'bank_code'             => $buyerBank->getAvailableBank()->code,
                'account_holder_name'   => $buyerBank->account_holder_name,
                'account_number'        => $buyerBank->account_number,
                'description'           => $buyerBank->description,
                'is_primary'            => $buyerBank->is_primary
            ];

            return response()->json(['response' => 'success', 'message' => 'Data fetched successfully', 'success' => 'true', 'data' => $data]);

        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BuyerBanks  $buyerBanks
     * @return \Illuminate\Http\Response
     */
    public function edit(BuyerBanks $buyerBanks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\AddBuyerBanksRequest  $request
     * @param  \App\Models\BuyerBanks  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddBuyerBanksRequest $request, $id)
    {
        if ($request->ajax()) {

            $userId = Auth::user()->id;

            if (!empty($userId)) {

                //Reset existing primary banks
                $resetPrimary = $request->isPrimary > BuyerBanks::NOTPRIMARY ? $this->resetPrimaryBank($userId) : true;

                if ($resetPrimary) {

                    $buyerBank = BuyerBanks::findOrFail($id);

                    $buyerBank->update([

                        'bank_id'               => $request->bankName,
                        'company_id'            =>  Auth::user()->default_company,
                        'account_holder_name'   =>  $request->bankAccountHolderName,
                        'account_number'        =>  $request->bankAccountNumber,
                        'description'           =>  $request->bankDescription,
                        'is_primary'            => $request->isPrimary,
                        'updated_by'            => $userId

                    ]);

                    buyerNotificationInsert(Auth::user()->id, 'Buyer Bank Updated', 'buyer_bank_updated_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                    broadcast(new BuyerNotificationEvent());
                    return response()->json(['response' => 'success', 'message' => 'Buyer bank updated successfully', 'success' => 'true']);

                }
            }


        }

        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if ($request->ajax()) {

            $buyerBank = BuyerBanks::findorfail($id);

            if ($buyerBank->is_primary == 0 ) {

                $buyerBank->delete();
                buyerNotificationInsert(Auth::user()->id, 'Buyer Bank Deleted', 'buyer_bank_deleted_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
                return response()->json(['response' => 'success', 'message' => __('profile.bank_removed'), 'success' => true]);

            } else {

                return response()->json(['response' => 'error', 'message' => __('profile.primary_bank_not_removed'), 'success' => false]);

            }
        }

        abort(404);
    }

    /**
     * Update existing resource in storage.
     *
     * @param  App\Model\Users  $userId
     * @return \Illuminate\Http\Response true or false
     */
    public function resetPrimaryBank($userId)
    {

        if (!empty(Auth::user()->id)) {

            try {
                $authUser = Auth::user();
                /**********begin:Buyer Bank details set permissions based on custom role******/
                $isOwner = User::checkCompanyOwner();
                if($isOwner == true || $authUser->hasPermissionTo('list-all buyer bank details')){
                    BuyerBanks::where('company_id', $authUser->default_company)
                        ->update([
                            'is_primary'    =>  BuyerBanks::NOTPRIMARY,
                            'updated_by'    =>  Auth::user()->id,
                        ]);
                }else {
                    BuyerBanks::where('user_id', $authUser->id)
                        ->where('company_id', $authUser->default_company)
                        ->update([
                            'is_primary'    =>  BuyerBanks::NOTPRIMARY,
                            'updated_by'    =>  Auth::user()->id,
                        ]);
                }

                /**********end:Buyer Bank details  set permissions based on custom role******/

            } catch (\Exception $e) {
                return false;

            }

            return true;
        }

        return false;

    }

    /**
     * Update existing primary resource in storage.
     *
     * @param  App\Model\BuyerBanks  $bankId
     * @return \Illuminate\Http\Response mixed
     */
    public function updatePrimaryBank(Request $request)
    {
        if ($request->ajax()) {

            $userId = Auth::user()->id;

            if (!empty($userId)) {

                $resetPrimary = $this->resetPrimaryBank($userId);

                if ($resetPrimary) {
                    $authUser = Auth::user();
                    /**********begin:Buyer Bank details set permissions based on custom role******/
                    $isOwner = User::checkCompanyOwner();
                    if($isOwner == true || $authUser->hasPermissionTo('list-all buyer bank details')){
                        BuyerBanks::where('company_id', $authUser->default_company)->where('id', $request->id)
                            ->update([
                                'is_primary'    => BuyerBanks::PRIMARY,
                                'updated_by'    => $userId
                            ]);
                    }else {
                        BuyerBanks::where('user_id', $authUser->id)
                            ->where('company_id', $authUser->default_company)
                            ->where('id', $request->id)
                            ->update([
                                'is_primary'    => BuyerBanks::PRIMARY,
                                'updated_by'    => $userId
                            ]);
                    }

                    /**********end:Buyer Bank details  set permissions based on custom role******/

                    return response()->json(['response' => 'success', 'message' => 'Buyer bank updated successfully', 'success' => 'true']);

                }

            }

        }
    }

    /**
     * Check existing primary resource in storage.
     *
     * @param App\Http\Request $request
     *
     * @return \Illuminate\Http\Response mixed
     */
    public function existPrimaryBank(Request $request)
    {
        if ($request->ajax()) {

            $buyerBank = BuyerBanks::findorfail($request->id);

            if ($buyerBank->is_primary == 1 ) {

                return response()->json(['response' => 'success', 'message' => __('profile.primary_bank_not_removed'), 'success' => true]);

            } else {
                return response()->json(['response' => 'error', 'success' => false]);

            }

        }


    }
}
