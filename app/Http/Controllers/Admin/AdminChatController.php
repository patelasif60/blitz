<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSentEvent;
use App\Http\Controllers\Controller;
use App\Jobs\SendActivationMailToSupplierEmailJob;
use App\Models\Company;
use App\Models\MongoDB\ChatGroup;
use App\Models\MongoDB\GroupChatCount;
use App\Models\MongoDB\GroupChatMember;
use App\Models\MongoDB\GroupChatMessage;
use App\Models\MongoDB\SupportChat;
use App\Models\MongoDB\SupportChatMember;
use App\Models\MongoDB\SupportChatMessage;
use App\Models\RfqProduct;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserCompanies;
use App\Models\UserSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;
use URL;

class AdminChatController extends Controller
{
    function adminChatView($type = 'Rfq')
    {
        $supplierRfq=$groupChatList=$supportChatList=$agentRfq='';
        if(auth()->user()->role_id ==  Role::SUPPLIER){
            $supplier = GroupChatMember::getSupplierGroupChatList($type);
            $supplierRfq = collect($supplier)->where('chatGroup','!=',null);
        }
        else if (auth()->user()->role_id == Role::AGENT) {
            $agent = GroupChatMember::getAgentGroupChatList($type);
            $agentRfq = collect($agent)->where('chatGroup', '!=', null);
        }
        else{
            $groupChatList = ChatGroup::getAdminGroupChatList($type);
        }

        $rfqChatList = ChatGroup::with(['groupChatMember'=>function($q){
            $q->where('user_id', Auth::user()->id)->select(['group_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
        }])->where('chat_type','Rfq')->get();
        $quoteChatList = ChatGroup::with(['groupChatMember'=>function($q){
            $q->where('user_id', Auth::user()->id)->select(['group_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
        }])->where('chat_type','Quote')->get();
        $supportChatList = SupportChat::with(['supportChatMember'=>function($q){
            $q->where('user_id', Auth::user()->id)->select(['support_chat_id','unread_message_count','user_id'])->where('unread_message_count', '>' ,0);
        }])->get();
        $rfqChatCount = collect($rfqChatList->toArray())->where('group_chat_member','!=',null);
        $quoteChatCount = collect($quoteChatList->toArray())->where('group_chat_member','!=',null);
        $supportChatCount = collect($supportChatList->toArray())->where('support_chat_member','!=',null);

        return view('admin/chat/chatView',['groupChatList'=>$groupChatList,'supplierRfq'=>$supplierRfq, 'agentRfq' => $agentRfq, 'header_name'=>$type, 'rfqCount' => $rfqChatCount->count(), 'quoteCount' => $quoteChatCount->count(), 'supportChatCount' => $supportChatCount->count()]);
    }

    public function adminChatWiseView(Request $request){
        if ($request->type != 'Support') {
            $supplierRfq = $groupChatList = $agentRfq = '';
            if (auth()->user()->role_id == Role::SUPPLIER) {
                $supplier = GroupChatMember::getSupplierGroupChatList($request->type);
                $supplierRfq = collect($supplier)->where('chatGroup', '!=', null);
            }
            else if (auth()->user()->role_id == Role::AGENT) {
                $agent = GroupChatMember::getAgentGroupChatList($request->type);
                $agentRfq = collect($agent)->where('chatGroup', '!=', null);
            }
            else {
                $groupChatList = ChatGroup::getAdminGroupChatList($request->type);
            }
            if ($request->type == 'Rfq') {
                $returnHTML = view('admin/chat/chatRfqDetailsModel', ['groupChatList' => $groupChatList, 'supplierRfq' => $supplierRfq, 'agentRfq' => $agentRfq, 'header_name' => $request->type])->render();
            } elseif ($request->type == 'Quote') {
                $returnHTML = view('admin/chat/chatGroupListModel', ['groupChatList' => $groupChatList, 'supplierRfq' => $supplierRfq, 'agentRfq' => $agentRfq, 'header_name' => $request->type])->render();
            }
        } else {
            //Support chat get data and return html
            $supportChatList = SupportChat::getAdminGroupChatList();
            $returnHTML = view('admin/chat/chatSupportListModel', ['supportChatList' => $supportChatList, 'header_name' => $request->type, 'supportChatCount' => $supportChatCount??''])->render();
        }



        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
    function getNewChatList(Request $request)
    {
        $type = $request->data_value ;
        if(auth()->user()->role_id ==  Role::SUPPLIER){
            $newChatList = ChatGroup::getSupplierNewChatList($type);
        }else if(auth()->user()->role_id ==  Role::AGENT){
            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
            $newChatList = ChatGroup::getAgentNewChatList($type,$assignedCategory);
        }
        else {
            $newChatList = ChatGroup::getAdminNewGroupChatList($type);
        }
        if($type == 'Rfq'){
            $returnHTML= view('admin/chat/newGroupChatList',['newChatList'=>$newChatList,'header_name'=>$type])->render();
        }elseif ($type == 'Quote'){
            $returnHTML= view('admin/chat/newGroupQuoteChatList',['newChatList'=>$newChatList,'header_name'=>$type])->render();
        }
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function adminBackGetDataRfq(Request $request){
        $supplierRfq=$groupChatList=$agentRfq='';
        if(auth()->user()->role_id ==  Role::SUPPLIER){
            $supplierRfq = GroupChatMember::getSupplierGroupChatList($request->header_name);
            $supplierRfq = collect($supplierRfq)->where('chatGroup','!=',null);
        }
        elseif(auth()->user()->role_id ==  Role::AGENT){
            $agent = GroupChatMember::getAgentGroupChatList($request->header_name);
            $agentRfq = collect($agent)->where('chatGroup', '!=', null);
        }
        else{
            $groupChatList = ChatGroup::getAdminGroupChatList($request->header_name);
        }
        $returnHTML = view('admin/chat/backChatList',['groupChatList'=>$groupChatList,'supplierRfq'=>$supplierRfq,'agentRfq'=>$agentRfq,'header_name'=>$request->header_name])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function getMoreInfoList(Request $request){
        $productInfo = ChatGroup::getProductInfo($request->group_chat_id,$request->header_name);
        $supplierList = GroupChatMember::getChatSupplierList($request->id,$request->group_chat_id,$request->header_name);
        $userSupplierList = GroupChatMember::getUserSupplierList();
        $supplier = array();
        foreach ($userSupplierList as $value){
            $supplier[] = $value->supplier_id;
        }
        $returnHTML= view('admin/chat/moreInfoList',['productInfo'=>$productInfo,'supplierList'=>$supplierList,'userSupplierList'=>$supplier,'chat_id'=>$request->group_chat_id,'group_id'=>$request->id,'header_name'=>$request->header_name])->render();;
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function getChatDataView(Request $request){
        $rfqMessagesList = GroupChatMessage::getGroupChatMessages($request->chat_id, $request->id, $request->header_name);
        //reset count set 0 start
        GroupChatMember::messageRead($request->chat_id, (int)Auth::user()->id, (int)$request->company_id);
        //reset count end
        $quickMessages = GroupChatMessage::getQucikMessages(auth()->user()->role_id,$request->header_name);
        if ($request->header_name == 'Rfq'){
            $returnHTML = view('admin/chat/chatDetailsModel', ['header_group_name' => $request->group_name, 'chat_id' => $request->chat_id, 'id' => $request->id, 'type'=>$request->header_name, 'allMessage' => $rfqMessagesList,'quickMessages'=>$quickMessages, 'company_id' => $request->company_id])->render();
        } elseif ($request->header_name == 'Quote') {
            $returnHTML = view('admin/chat/chatQuoteDetailsModel', ['header_group_name' => $request->group_name, 'chat_id' => $request->chat_id, 'id' => $request->id, 'type'=>$request->header_name, 'allMessage' => $rfqMessagesList,'quickMessages'=>$quickMessages, 'company_id' => $request->company_id])->render();
        }
        return response()->json(array('success' => true, 'html' => $returnHTML, 'countChatMessage' => count($rfqMessagesList)));
    }

    function createChatBackend(Request $request){

        if(auth()->user()->role_id ==  Role::SUPPLIER){
            $userName = getCompanyByUserId(Auth::user()->id);
        }else if(auth()->user()->role_id ==  Role::AGENT){
            $userName = getUserName(Auth::user()->id);
        }else {
            $userName = 'blitznet Team';
        }

        $data = array(
            "chat_id" => (int)$request->id,
            "chat_type" => $request->chat_type,
            "group_name" => $request->group_name,
            "user_id" => (int)Auth::user()->id,
            "user_type" => (int)Auth::user()->role_id,
            "message" => $request->message??'',
            "user_name" => $userName,
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
            $groupMember = GroupChatMember::saveGroupMember($group->_id, $request->chat_type, (int)$request->id, 0, 0, (int)$request->company_id??0);
            $geroupMemberExits = GroupChatMember::where('group_chat_id', $group->_id)->get();
        }

        $html = '';
        if (!empty($request->file())){
            $html .= $this->checkFiles($data, $group->_id, 2, $request->file()??[],$countExitMember)->getData()->html;
        }

        if (!empty($request->message)){
            $html .= $this->checkMessage($data, $group->_id, 1, $request->message, $countExitMember)->getData()->html;
        }

        return response()->json(array('success' => true, 'html' => $html));
    }

    public function checkFiles($data, $id, $type, $files,$countExitMember){
        $innerHtmlDisplay = '';
        $htmlView = '';
        foreach ($files as $file){
            $fileName = Str::random(10) . '_' . time().'_chat_image_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/chat_image',$fileName, 'public');
            $mimtype = $file->getMimeType();
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
        $messages = GroupChatMessage::saveMessage($id, $data, $type);
        if ($countExitMember != 0){
            GroupChatMember::chatCountIncrement($id, Auth::user()->id);
        }
        broadcast(new MessageSentEvent($data));
        $htmlView .= $this->messageHtml($message, changeTimeFormat(now()))->getData()->html;
        return response()->json(array('success' => true, 'html' => $htmlView));
    }

    public function messageHtml($innerHtmlDisplay, $date){
        $userName = 'blitznet Team';
        if (auth()->user()->role_id == 3){
            $userName = getCompanyByUserId(Auth::user()->id);
        }
        $viewMessageHtml = '<div class="chat-message ms-auto text-end mb-2">
                        <div class="card message-from-blitznet">
                            <div class="card-body p-1 px-2">
                                <div class="messanger-name fw-bold mb-0">'.$userName.'</div>
                                <div class="card-text message mb-1 f-13">'.$innerHtmlDisplay.'</div>
                                <div class="timer d-flex justify-content-end mt-1"><small class="sender-message-time text-muted fw-bold">'.$date.'</small></div>
                            </div>
                        </div>
                    </div>';

        return response()->json(array('html' => $viewMessageHtml));
    }

    public function messageRead(Request $request)
    {
        $geroupMemberExits = GroupChatMember::where(['group_chat_id'=>$request->group_chat_id,'user_id'=>auth()->user()->id])->count();
        if ($geroupMemberExits===0){
            return response()->json(array('success' => false));
        }
        $result = GroupChatMember::messageRead($request->group_chat_id,auth()->user()->id);
        if ($result) {
            return response()->json(array('success' => true));
        }else{
            return response()->json(array('success' => false));
        }
    }

    public function getFrontSearchData(Request $request){
        $rfqChatList = ChatGroup::getAdminSearchGroupChatList((string)$request->string??'', $request->chat_type);
        $returnHTML = view('admin/chat/chatSearchList' , ['rfqChatList'=>$rfqChatList, 'header_name' => $request->chat_type, 'chatType' => $request->chat_type])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /** @ekta
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * new serch data
     */
    public function getNewBackSearchData(Request $request){
        if(auth()->user()->role_id ==  Role::SUPPLIER){
            $newChatList = ChatGroup::getSearchSupplierNewChatList($request->string??'', $request->chat_type);
        }else if(auth()->user()->role_id ==  Role::AGENT){
            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
            $newChatList = ChatGroup::getSearchAgentNewGroupChatList($request->string??'', $request->chat_type, $assignedCategory);
        }else{
            $newChatList = ChatGroup::getSearchAdminNewGroupChatList($request->string??'', $request->chat_type);
        }
        $returnHTML= view('admin/chat/newSearchChatList',['newChatList'=>$newChatList,'header_name'=>$request->chat_type])->render();;
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
    @ekta - 10-10-22
     * display backend admin suppoer chat
     */
    function getSupportChatDataView(Request $request){

        $supportMessagesList = SupportChatMessage::getSupportChatMessages($request);
        //reset count set 0 start
        SupportChatMember::messageRead($request->id, (int)Auth::user()->id, (int)$request->company_id);

        //company message count
        $countCompany = SupportChatMember::getCountCompanyWiseUnreadMsg(Auth::user()->id,Auth::user()->role_id,$request->company_id);

        //reset count end
        $quickMessages = SupportChatMessage::getQucikMessages(auth()->user()->role_id,'Support');

        $returnHTML = view('admin/chat/chatSupportDetailsModel', ['header_group_name' => $request->header_name, 'id' => $request->id, 'allMessage' => $supportMessagesList, 'quickMessages'=>$quickMessages, 'company_id' => $request->company_id, 'user_id' => $request->user_id])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML, 'countChatMessage' => count($supportMessagesList), 'countCompany' => $countCompany, 'companyId'=>$request->company_id));
    }

    /**
    @ekta - 10-10-22
     * admin responce suppoer chat, store chat data
     */
    function createSupportChatBackend(Request $request){
        $userName = 'blitznet Team';
        $data = array(
            "support_chat_id" => $request->id,
            "message" => $request->message??'',
            "user_id" => (int)Auth::user()->id,
            "user_type" => (int)Auth::user()->role_id,
            "company_id" => 0,
        );

        $support = SupportChat::find($request->id);

        //check support member added or not
        $supportChatMemberExits = SupportChatMember::where('support_chat_id', $request->id)->get();
        $countExitMember = count($supportChatMemberExits);

        $html = '';
        if (!empty($request->file())){
            $html .= $this->checkSupportFiles($data, $support->_id, 2, $request->file()??[],$countExitMember)->getData()->html;
        }

        if (!empty($request->message)){
            $html .= $this->checkSupportMessage($data, $support->_id, 1, $request->message, $countExitMember)->getData()->html;
        }

        return response()->json(array('success' => true, 'html' => $html));
    }

    /**
    @ekta - 10-10-22
     * function for insert message only
     */
    public function checkSupportMessage($data, $id, $type, $message, $countExitMember){
        $htmlView = '';
        unset($data['message']);
        $data += ['message' => $message, 'mimtype' => 0, 'message_type' => (int)$type];
        $messages = SupportChatMessage::saveMessage($id, $data);
        if ($countExitMember != 0){
            SupportChatMember::chatCountIncrement($id, Auth::user()->id);
        }
        broadcast(new MessageSentEvent($data));
        $htmlView .= $this->messageHtml($message, changeTimeFormat(now()))->getData()->html;
        return response()->json(array('success' => true, 'html' => $htmlView));
    }

    /**
    @ekta - 10-10-22
     * function for insert file only
     */
    public function checkSupportFiles($data, $id, $type, $files,$countExitMember){
        $innerHtmlDisplay = '';
        $htmlView = '';
        foreach ($files as $file){
            $fileName = Str::random(10) . '_' . time().'_chat_image_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/chat_image',$fileName, 'public');
            $mimtype = $file->getMimeType();
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

    /**
    @ekta - 12-10-22
     * function for serch support chat data only
     */
    public function getSearchSupportChatData(Request $request){
        $supportChatList = SupportChat::getSearchSupportChatData((string)$request->string??'');
        $returnHTML = view('admin/chat/chatSupportSearchList' , ['supportChatList'=>$supportChatList])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
}
