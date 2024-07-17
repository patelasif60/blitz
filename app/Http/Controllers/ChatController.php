<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Models\MongoDB\ChatGroup;
use App\Models\MongoDB\GroupChatCount;
use App\Models\MongoDB\GroupChatMember;
use App\Models\MongoDB\GroupChatMessage;
use App\Models\MongoDB\SupportChat;
use App\Models\MongoDB\SupportChatMember;
use App\Models\MongoDB\SupportChatMessage;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use URL;

class ChatController extends Controller
{
    function groupChatList(Request $request)
    {
        $data = array('chat_type' => Crypt::decrypt($request->data_value));
        $rfqChatList = ChatGroup::getGroupChatList($data);
        $returnHTML = view('dashboard/chat/chatGroupListModel' , ['rfqChatList'=>$rfqChatList, 'header_name' => $request->header_name, 'chatType' => Crypt::decrypt($request->data_value)])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function getNewChatList(Request $request){
        $dataList = getDataFromChatType($request->header_name);
        $dataListResult = ($request->header_name == 'Rfq') ? $dataList['rfqs'] : $dataList['quote'];
        $returnHTML = view('dashboard/chat/newGroupChatListModel' , ['newChatList' => $dataListResult, 'header_name' => $request->header_name])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function createNewChatView(Request $request){
        $messagesList = GroupChatMessage::getGroupChatMessages($request['chat_id'], $request['id'], $request['header_name']);
        //reset count set 0 start
        GroupChatMember::messageRead($request['chat_id'], (int)Auth::user()->id, (int)$request->company_id);
        //reset count end
        $quickMessages = GroupChatMessage::getQucikMessages(auth()->user()->role_id, $request->header_name);
        $returnHTML = view('dashboard/chat/chatViewModel', ['header_group_name' => $request->group_name, 'id' => $request->id, 'type' => $request->header_name, 'getMessageLists' => $messagesList, 'chat_id' => $request->chat_id,'quickMessages'=>$quickMessages, 'company_id' => $request->company_id])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function createChat(Request $request){
        if (Auth::check() == false){
            return redirect('/signin');
        }
        $data = array(
            "chat_id" => (int)$request->id,
            "chat_type" => $request->chat_type,
            "group_name" => $request->group_name,
            "user_id" => (int)Auth::user()->id,
            "user_type" => (int)Auth::user()->role_id,
            "message" => $request->message??'',
            'user_name' => getCompanyByUserId(Auth::user()->id),
            "company_id" => (int)$request->company_id??0,
            'current_time' => changeTimeFormat(now())
        );

        if (!empty($request->chat_id)){
            $group = ChatGroup::find($request->chat_id);
        } else {
            $group = ChatGroup::createUpdateChatGroup($data);
        }

        //check group member added or not
        $geroupMemberExits = GroupChatMember::where('group_chat_id', $group->_id)->get();
        $countExitMember = count($geroupMemberExits);
        if ($countExitMember == 0){
            $groupMember = GroupChatMember::saveGroupMember($group->_id, $request->chat_type, (int)$request->id, (int)Auth::user()->id, 1, (int)$request->company_id??0);
            $geroupMemberExits = GroupChatMember::where('group_chat_id', $group->_id)->get();
        }
        $html = '';
        if (!empty($request->file())){
            $html .= $this->checkFiles($data, $group->_id, 2, $request->file()??[], $countExitMember)->getData()->html;
        }

        if (!empty($request->message)){
            $html .= $this->checkMessage($data, $group->_id, 1, $request->message, $countExitMember)->getData()->html;
        }

        return response()->json(array('success' => true, 'html' => $html));
    }

    public function checkFiles($data, $id, $type, $files, $countExitMember){
        $innerHtmlDisplay = '';
        $htmlView = '';
        foreach ($files as $file){
            $fileName = Str::random(10) . '_' . time().'_chat_image_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/chat_image',$fileName, 'public');
            $mimtype = $file->getMimeType();
            unset($data['mimtype']);
            if ($mimtype == 'image/jpeg' || $mimtype == 'image/png' || $mimtype == 'image/jpg'){
                $mimtypeEnum = 1;
                $innerHtmlDisplay = "<a href='".url('storage/'.$filePath)."' download><img src='".url('storage/'.$filePath)."' class='mw-100'></a>";
            } else if($mimtype == 'application/pdf'){
                $mimtypeEnum = 2;
                $url = URL::asset('assets/images/PDF_icon.png');
                $innerHtmlDisplay = "<a href='".url('storage/'.$filePath)."' download><img src='".$url."' class='mw-100'></a>";
            }
            unset($data['message']);
            $data += ['message' => $filePath, 'messageImage' => url('storage/'.$filePath), 'mimtype' => $mimtypeEnum, 'message_type' => (int)$type];
            $messages = GroupChatMessage::saveMessage($id, $data);
            if ($countExitMember != 0) {
                GroupChatMember::chatCountIncrement($id, Auth::user()->id);
            }
            broadcast(new MessageSentEvent($data));
            $htmlView .= $this->messageHtml($innerHtmlDisplay, changeTimeFormat(now()))->getData()->html;
        }
        return response()->json(array('success' => true, 'html' => $htmlView));
    }

    public function checkMessage($data, $id, $type, $message, $countExitMember){
        $htmlView = '';
        unset($data['message']);
        $data += ['message' => $message, 'mimtype' => 0, 'message_type' => (int)$type];
        $messages = GroupChatMessage::saveMessage($id, $data, $type, (int)$data['company_id']);
        if ($countExitMember != 0) {
            GroupChatMember::chatCountIncrement($id, Auth::user()->id);
        }
        broadcast(new MessageSentEvent($data));
        $htmlView .= $this->messageHtml($message, changeTimeFormat(now()))->getData()->html;
        return response()->json(array('success' => true, 'html' => $htmlView));
    }

    public function messageHtml($innerHtmlDisplay, $date){
        $viewMessageHtml = '<div class="col-md-12 d-flex align-items-start buyerchatside px-2 pb-1">
                            <div class="col-md-9 chatdetailfromuser">
                                <div class="p-2">
                                    <div class="name">You</div>
                                    <div class="text">'.$innerHtmlDisplay.'</div>
                                    <div class="time">'.$date.'</div>
                                </div>
                            </div>
                            <div class="col-md-auto ps-2 mt-1">
                                <span class="userfonticon">
                                    <i class="fa fa-user" style="font-size: 14px;"></i>
                                </span>
                            </div>
                        </div>';

        return response()->json(array('html' => $viewMessageHtml));
    }

    public function getRfqproductDetails(Request $request){
        $productList="";
        if ($request->type == 'Rfq'){
            $productList =RfqProduct::where(['rfq_id'=>$request['rfq_id'],'is_deleted'=>0])->with(['unit'=>function($q) {
                $q->select(['units.id','name']);
            }])->get(['id','category','sub_category','product','quantity','unit_id']);
        }elseif ($request->type == 'Quote') {
            $productList =QuoteItem::where('quote_id',$request['rfq_id'])->with(['rfqProduct'=>function($r) {
                $r->select(['id','category','sub_category','product','quantity','unit_id'])->where("is_deleted",0)->with('unit:id,name');
            }])->with(['quoteDetails'=>function($d){
                $d->select(['id','final_amount','supplier_id'])->with('supplier:id,name');
            }])->get(['id','quote_id','supplier_id','product_id','rfq_product_id']);
        }
        $returnHTML = view('dashboard/chat/productDetailsModel', ['productList' => $productList, 'header_group_name' => $request->name,'chat_type'=>$request->type])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));

    }

    public function getGroupChattypeCount(Request $request){
        $isOwner = User::checkCompanyOwner();
        $rfqChatList = ChatGroup::with(['groupChatMember'=>function($q) use($isOwner){
            if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer rfqs')){
                $q->orWhere('is_owner', 1)->where(['company_id' => (int)Auth::user()->default_company, 'user_id' => (int)Auth::user()->id])->select(['group_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
            } else {
                $q->where(['user_id' => (int)Auth::user()->id, 'is_owner' => 1, 'company_id' => (int)Auth::user()->default_company])->select(['group_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
            }
        }])->where('chat_type','Rfq')->get();
        $quoteChatList = ChatGroup::with(['groupChatMember'=>function($q) use($isOwner){
            if($isOwner == true || (Auth::user()->hasPermissionTo('list-all buyer rfqs') && Auth::user()->hasPermissionTo('list-all buyer quotes'))){
                $q->orWhere('is_owner', 1)->where(['user_id' => (int)Auth::user()->id, 'company_id' => (int)Auth::user()->default_company])->select(['group_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
            } else {
                $q->where(['user_id' => (int)Auth::user()->id, 'is_owner' => 1, 'company_id' => (int)Auth::user()->default_company])->select(['group_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
            }
        }])->where(['chat_type'=>'Quote', 'company_id' => (int)Auth::user()->default_company])->get();

        $supportChatList = SupportChatMember::where(['user_id' => (int)Auth::user()->id, 'company_id' => (int)Auth::user()->default_company])->pluck('unread_message_count')->first();
        $rfqChatCount = collect($rfqChatList->toArray())->where('group_chat_member','!=',null);
        $quoteChatCount = collect($quoteChatList->toArray())->where('group_chat_member','!=',null);
        return response()->json(array('success' => true, 'rfqCount' => $rfqChatCount->count(), 'quoteCount' => $quoteChatCount->count(), 'supportCount' => $supportChatList));
    }

    public function getSearchData(Request $request){
        $rfqChatList = ChatGroup::getSearchGroupChatList((string)$request->string??'', $request->type);
        $returnHTML = view('dashboard/chat/chatSearchList' , ['rfqChatList'=>$rfqChatList, 'header_name' => $request->type, 'chatType' => $request->type])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getNewSearchData(Request $request){
        $dataList = getNewSearchDataFromChat($request->string, $request->type);
        $returnHTML = view('dashboard/chat/newSearchChatList' , ['newChatList' => $dataList, 'header_name' => $request->type,])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getChatQuoteHistoryData(Request $request){
        $quoteId = $request->id;
        $type = $request->header_name;
        $chatHistory = getChatHistoryRfqById($quoteId,$type);
        $historyhtml = view('dashboard/chat/quoteChatHistoryModal', ['quoteId' => $request->id, 'quoteNumber' => $request->group_name, 'chatHistory' => $chatHistory])->render();
        return response()->json(array('success' => true, 'html' => $historyhtml));
    }

    //support chat

    public function supportChatList(Request $request){
        $messagesList = SupportChatMessage::getGroupChatMessages($request['support_chat_id']);
        //reset count set 0 start
        if (!empty($request['support_chat_id'])){
            SupportChatMember::messageRead($request['support_chat_id'], (int)Auth::user()->id, (int)$request->company_id);
        }
        //reset count end
        $quickMessages = SupportChatMessage::getQucikMessages(auth()->user()->role_id, $request->header_name);
        $returnHTML = view('dashboard/chat/chatSupportViewModel', ['header_group_name' => $request->header_name, 'type' => $request->header_name, 'getMessageLists' => $messagesList, 'chat_id' => $request->chat_id,'quickMessages'=>$quickMessages, 'company_id' => $request->company_id])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function createSupportChat(Request $request){
        if (Auth::check() == false){
            return redirect('/signin');
        }
        $data = array(
            "user_id" => (int)Auth::user()->id,
            "user_type" => (int)Auth::user()->role_id,
            "message" => $request->message??'',
            'user_name' => getCompanyByUserId(Auth::user()->id),
            "company_id" => (int)$request->company_id??0,
            'current_time' => changeTimeFormat(now())
        );

        if (!empty($request->chat_id)){
            $group = SupportChat::find($request->chat_id);
        } else {
            $group = SupportChat::createUpdateChatGroup($data);
        }

        //check group member added or not
        $geroupMemberExits = SupportChatMember::where('support_chat_id', $group->_id)->get();
        $countExitMember = count($geroupMemberExits);
        if ($countExitMember == 0){
            $groupMember = SupportChatMember::saveGroupMember($group->_id, (int)Auth::user()->id, 1, (int)$request->company_id??0);
            $geroupMemberExits = SupportChatMember::where('support_chat_id', $group->_id)->get();
        }
        $html = '';
        if (!empty($request->file())){
            $html .= $this->checkFilesSupport($data, $group->_id, 2, $request->file()??[], $countExitMember)->getData()->html;
        }

        if (!empty($request->message)){
            $html .= $this->checkMessageSupport($data, $group->_id, 1, $request->message, $countExitMember)->getData()->html;
        }

        return response()->json(array('success' => true, 'html' => $html));
    }

    public function checkMessageSupport($data, $id, $type, $message, $countExitMember){
        $htmlView = '';
        unset($data['message']);
        $data += ['message' => $message, 'mimtype' => 0, 'message_type' => (int)$type];
        $messages = SupportChatMessage::saveMessage($id, $data, $type, (int)$data['company_id']);
        if ($countExitMember != 0) {
            SupportChatMember::chatCountIncrement($id, Auth::user()->id);
        }
        broadcast(new MessageSentEvent($data));
        $htmlView .= $this->messageHtml($message, changeTimeFormat(now()))->getData()->html;
        return response()->json(array('success' => true, 'html' => $htmlView));
    }

    public function checkFilesSupport($data, $id, $type, $files, $countExitMember){
        $innerHtmlDisplay = '';
        $htmlView = '';
        foreach ($files as $file){
            $fileName = Str::random(10) . '_' . time().'_chat_image_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/chat_image',$fileName, 'public');
            $mimtype = $file->getMimeType();
            unset($data['mimtype']);
            if ($mimtype == 'image/jpeg' || $mimtype == 'image/png' || $mimtype == 'image/jpg'){
                $mimtypeEnum = 1;
                $innerHtmlDisplay = "<a href='".url('storage/'.$filePath)."' download><img src='".url('storage/'.$filePath)."' class='mw-100'></a>";
            } else if($mimtype == 'application/pdf'){
                $mimtypeEnum = 2;
                $url = URL::asset('assets/images/PDF_icon.png');
                $innerHtmlDisplay = "<a href='".url('storage/'.$filePath)."' download><img src='".$url."' class='mw-100'></a>";
            }
            unset($data['message']);
            $data += ['message' => $filePath, 'messageImage' => url('storage/'.$filePath), 'mimtype' => $mimtypeEnum, 'message_type' => (int)$type];
            $messages = SupportChatMessage::saveMessage($id, $data);
            if ($countExitMember != 0) {
                SupportChatMember::chatCountIncrement($id, Auth::user()->id);
            }
            broadcast(new MessageSentEvent($data));
            $htmlView .= $this->messageHtml($innerHtmlDisplay, changeTimeFormat(now()))->getData()->html;
        }
        return response()->json(array('success' => true, 'html' => $htmlView));
    }
}
