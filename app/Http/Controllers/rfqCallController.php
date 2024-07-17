<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rfqCall;
use Illuminate\Support\Facades\DB;

class rfqCallController extends Controller
{
    //
    function create(Request $request)
    {
        $rfqComment = new rfqCall;
        $rfqComment->rfq_id = $request->rfq_id;
        $rfqComment->comment = $request->comment;
        $rfqCommentData = $rfqComment->save();

		//$rfqCalls = rfqCall::all()->where('rfq_id',$request->rfq_id)->sortByDesc("created_at");
		//$rfqCalls = rfqCall::orderBy('created_at')->where('rfq_id',$request->rfq_id)->get();
		$rfqCalls = DB::table('rfqs_call')
		->where('rfq_id', $request->rfq_id)
		->orderBy('created_at', 'desc')
		->get();

		$rfqCallsResult = json_decode($rfqCalls, true);

        if($rfqCommentData){
			return response()->json(array('success' => true, 'data' => $rfqCommentData, 'rfqCallsResult'=> $rfqCallsResult));
		}else{
			return response()->json(array('success' => false, 'data' => $rfqCommentData, 'rfqCallsResult'=> $rfqCallsResult));
		}
    }
}
