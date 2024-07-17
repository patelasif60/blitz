<?php

namespace App\Http\Controllers;

use App\Models\AdminFeedbacks;
use Illuminate\Http\Request;
use Auth;

class AdminFeedbackController extends Controller
{
    public function getFeedbackList($id, $type, $render_html = 0){
        $getFeedbacks = AdminFeedbacks::with('user:id,firstname,lastname,profile_pic')->with('reason')->where(['feedback_id' => $id, 'feedback_type' => $type])->orderBy('updated_at', 'DESC')->get()->toArray();
        $html = view('admin/commanFeedbackForm', ['getFeedbacks' => $getFeedbacks])->render();
        if ($render_html == 0){
            return response()->json(array('success' => true, 'feedbackHtml' => $html??[]));
        } else {
            return $html??[];
        }
    }

    public function addUpdateFeedback(Request $request){
        if (isset($request->id) && !empty($request->id)){
            $data = ['reason_id' => $request->comment, 'feedback_id' => $request->feedback_id, 'feedback_type' => $request->feedback_type, 'user_id' => Auth::user()->id, 'id' => $request->id];
            AdminFeedbacks::where('id',$request->id)->update($data);
        } else {
            $data = ['reason_id' => $request->comment, 'feedback_id' => $request->feedback_id, 'feedback_type' => $request->feedback_type, 'user_id' => Auth::user()->id];
            AdminFeedbacks::create($data);
        }
        $html = $this->getFeedbackList($request->feedback_id, $request->feedback_type, 1);
        return response()->json(array('success' => true, 'feedbackHtml' => $html));
    }

    public function editFeedback($id){
        $getFeedback = AdminFeedbacks::where('id', $id)->first();
        return response()->json(array('success' => true, 'feedback' => $getFeedback));
    }

    public function deleteFeedback(Request $request){
        $feedback = AdminFeedbacks::find($request->id);
        if (!empty($feedback)){
            AdminFeedbacks::where('id', $request->id)->delete();
            $html = $this->getFeedbackList($request->feedback_id, $request->feedback_type, 1);
            return response()->json(array('success' => true,'feedbackHtml' => $html));
        } else {
            $html = $this->getFeedbackList($request->feedback_id, $request->feedback_type, 1);
            return response()->json(array('success' => false,'feedbackHtml' => $html));
        }
    }
}
