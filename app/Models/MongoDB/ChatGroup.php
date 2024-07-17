<?php

namespace App\Models\MongoDB;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\User;
use App\Models\UserRfq;
use App\Models\UserSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model;

use Auth;

class ChatGroup extends Model
{
    use HybridRelations;
    protected $connection = 'mongodb';
    protected $collection = 'group_chats';

    protected $fillable = ['chat_type', 'chat_id', 'group_name', 'user_type', 'user_role_id','user_id', 'company_id', 'owner_id'];
    protected $dates = ['created_at','deleted_at'];

    public static function createUpdateChatGroup($data){
        $result = self::where(['chat_type'=>$data['chat_type'],'chat_id'=>$data['chat_id']])->first();
        if (is_null($result)) {
            if ($data['chat_type'] == 'Rfq'){
                $data['owner_id'] = Rfq::find($data['chat_id'])->rfqUser->id;
            }
            if ($data['chat_type'] == 'Quote'){
                $rfq_id = Quote::find($data['chat_id'])->rfq_id;
                $data['owner_id'] = Rfq::find($rfq_id)->rfqUser->id;
            }
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public static function getGroupChatList($data){
        $isOwner = User::checkCompanyOwner();
        if($data['chat_type'] == 'Rfq') {
            if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer rfqs')){
                $rfqChatList = self::with(['groupChatMember'=>function($q){
                    $q->where(['user_id' => (int)Auth::user()->id, 'company_id' => (int)Auth::user()->default_company])->select(['group_chat_id','unread_message_count','user_id', 'company_id', 'check_permission']);
                }])->where("chat_type", $data['chat_type'])->where(['company_id'=> (int)Auth::user()->default_company])->orderBy('_id', 'desc')->get(['_id','chat_id','created_at','group_name', 'company_id'])->toArray();
            } else {
                $rfqChatList = self::with(['groupChatMember'=>function($q) {
                    $q->where(['company_id' => Auth::user()->default_company, 'user_id' => Auth::user()->id])->select(['group_chat_id','unread_message_count','user_id']);
                }])->where("chat_type", $data['chat_type'])->where(['company_id'=> Auth::user()->default_company])->orderBy('_id', 'desc')->get(['_id','chat_id','created_at','group_name', 'company_id'])->toArray();
            }
        }elseif ($data['chat_type'] == 'Quote'){
            if (Auth::user()->hasPermissionTo('list-all buyer rfqs') && Auth::user()->hasPermissionTo('list-all buyer quotes')){
                $rfqChatList =  self::with(['groupChatMember' => function ($q) {
                    $q->where(['company_id' => (int)Auth::user()->default_company, 'user_id' => (int)Auth::user()->id])->select(['group_chat_id', 'unread_message_count', 'user_id', 'company_id', 'check_permission']);
                }])->with(['quote'=> function ($q) {
                    $q->select(['id', 'quote_number', 'rfq_id'])->with('rfq:id,reference_number,company_id');
                }])->where("chat_type", $data['chat_type'])->where(['company_id'=> Auth::user()->default_company])->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name', 'company_id'])->toArray();
            } else {
                $rfqChatList =  self::with(['groupChatMember' => function ($q) {
                    $q->where(['company_id' => (int)Auth::user()->default_company, 'user_id' => (int)Auth::user()->id, 'is_owner' => 1])->select(['group_chat_id', 'unread_message_count', 'user_id', 'company_id', 'check_permission']);
                }])->with(['quote'=> function ($q) {
                    $q->select(['id', 'quote_number', 'rfq_id'])->with('rfq:id,reference_number,company_id');
                }])->where("chat_type", $data['chat_type'])->where(['company_id'=> (int)Auth::user()->default_company, 'is_owner' => 1])->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name', 'company_id'])->toArray();
            }
        }
        return $rfqChatList;
    }

    public static function getSearchGroupChatList($string, $type){
        $isOwner = User::checkCompanyOwner();
        if(empty($string)){
            return self::getGroupChatList(['chat_type' => $type]);
        }
        if($type == 'Rfq') {
            if ($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer rfqs')){
                return self::with(['groupChatMember' => function ($q) {
                    $q->where(['company_id' => Auth::user()->default_company, 'user_id' => (int)Auth::user()->id,])->select(['group_chat_id', 'unread_message_count', 'user_id']);
                }])->orWhere('group_name', 'like', '%' . $string . '%')->where("chat_type", $type)->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name'])->toArray();
            } else {
                return self::with(['groupChatMember' => function ($q) {
                    $q->where(['user_id'=> Auth::user()->id, 'company_id' => Auth::user()->default_company, 'is_owner' => 1])->select(['group_chat_id', 'unread_message_count', 'user_id']);
                }])->orWhere('group_name', 'like', '%' . $string . '%')->where(["chat_type" => $type, 'owner_id' => Auth::user()->id])->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name'])->toArray();
            }
        }elseif ($type == 'Quote'){
            if (Auth::user()->hasPermissionTo('list-all buyer rfqs') && Auth::user()->hasPermissionTo('list-all buyer quotes')){
                return self::with(['groupChatMember' => function ($q) {
                    $q->where(['company_id' => Auth::user()->default_company, 'user_id' => (int)Auth::user()->id,])->select(['group_chat_id', 'unread_message_count', 'user_id']);
                }])->with(['quote'=> function ($q) {
                    $q->select(['id', 'quote_number', 'rfq_id'])->with('rfq:id,reference_number,company_id');
                }])->orWhere('group_name', 'like', '%' . $string . '%')->where("chat_type", $type)->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name'])->toArray();
            } else {
                return self::with(['groupChatMember' => function ($q) {
                    $q->where(['user_id' => Auth::user()->id, 'company_id' => Auth::user()->default_company, 'is_owner' => 1])->select(['group_chat_id', 'unread_message_count', 'user_id']);
                }])->with(['quote'=> function ($q) {
                    $q->select(['id', 'quote_number', 'rfq_id'])->with('rfq:id,reference_number,company_id');
                }])->orWhere('group_name', 'like', '%' . $string . '%')->where(["chat_type"=> $type, 'owner_id' => Auth::user()->id])->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name'])->toArray();
            }
        }
    }

    public static function getAdminSearchGroupChatList($string, $type){
        if(empty($string)){
            return self::getGroupChatList(['chat_type' => $type]);
        }
        if($type == 'Rfq') {
            return self::with(['groupChatMember' => function ($q) {
                $q->where('user_id', Auth::user()->id)->select(['group_chat_id', 'unread_message_count', 'user_id']);
            }])->orWhere('group_name', 'like', '%' . $string . '%')->where("chat_type", $type)->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name'])->toArray();
        }elseif ($type == 'Quote'){
            return self::with(['groupChatMember' => function ($q) {
                $q->where('user_id', Auth::user()->id)->select(['group_chat_id', 'unread_message_count', 'user_id']);
            }])->with(['quote'=> function ($q) {
                $q->select(['id', 'quote_number', 'rfq_id'])->with('rfq:id,reference_number');
            }])->orWhere('group_name', 'like', '%' . $string . '%')->where("chat_type", $type)->orderBy('_id', 'desc')->get(['_id', 'chat_id', 'created_at', 'group_name'])->toArray();
        }
    }

    public static function getAdminGroupChatList($type){
        if($type == 'Rfq'){
            $return = self::with(['groupChatMember'=>function($q){
                $q->where('user_id', Auth::user()->id)->select(['group_chat_id','unread_message_count','user_id', 'company_id']);
            }])->where("chat_type", $type)->orderBy('_id', 'desc')->get(['_id','chat_id','created_at','group_name', 'company_id']);
            $r = collect($return->toArray())->where('group_chat_member','!=',null);
        }elseif($type == 'Quote'){
            $return = self::with(['groupChatMember'=>function($q){
                $q->where('user_id', Auth::user()->id)->select(['group_chat_id','unread_message_count','user_id', 'company_id']);
            }])->with(['quote'=> function ($q) {
                $q->select(['id','rfq_id'])->with('rfq:id,reference_number');
            }])->where("chat_type", $type)->orderBy('_id', 'desc')->get(['_id','chat_id','created_at','group_name', 'company_id']);
            $r = collect($return->toArray())->where('group_chat_member','!=',null);
        }
        return $r;
    }

    public function getAdminNewGroupChatList($type){
        if($type == 'Rfq') {
            $dataList = Rfq::with('chatGroupRfq:_id,chat_type,chat_id,group_name,company_id')->where(['rfqs.is_deleted' => 0])->orderBy('id', 'desc')->get(['id', 'reference_number', 'company_id']);
        }elseif ($type == 'Quote'){
            $dataList = Quote::with('chatGroupQuote:_id,chat_type,chat_id,group_name,company_id')->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')->with('rfq:reference_number,id,company_id')->whereNotIn('quotes.status_id', [5,3,4])->where(['quotes.is_deleted' => 0])->orderBy('id', 'desc')->get(['quotes.id', 'quote_number', 'rfq_id', 'rfqs.company_id']);
        }
        return $dataList;
    }

    public function getSearchAdminNewGroupChatList($string, $type){
        if (empty($string)){
            return self::getAdminNewGroupChatList($type);
        }
        if($type == 'Rfq') {
            return Rfq::with('chatGroupRfq:_id,chat_type,chat_id,group_name')->orWhere('reference_number', 'like', '%' . $string . '%')->where([ 'is_deleted' => 0])->orderBy('id', 'desc')->get(['id', 'reference_number']);
        }elseif ($type == 'Quote') {
            return Quote::with('chatGroupQuote:_id,chat_type,chat_id,group_name')->orWhere('quote_number', 'like', '%' . $string . '%')->with('rfq:reference_number,id')->whereNotIn('quotes.status_id', [5,3,4])->where(['is_deleted' => 0])->orderBy('id', 'desc')->get(['id', 'quote_number', 'rfq_id']);
        }
    }

    public function getSearchSupplierNewChatList($string, $type){
        $supplier_id = getSupplierByLoginId(auth()->user()->id);

        if ($type == 'Rfq'){
            $rfqs = Rfq::with(['chatGroupRfq:_id,chat_type,chat_id,group_name','rfqSuppliers' =>function($q) use ($supplier_id){
                $q->where('supplier_id', $supplier_id)->select(['supplier_id']);
            }])->orWhere('rfqs.reference_number', 'like', '%'.$string.'%')->where('rfqs.is_deleted', 0)->orderBy('rfqs.id', 'desc')->groupBy('rfqs.id')->get(['rfqs.id','rfqs.reference_number']);
            $rfqs = json_decode(collect($rfqs->toArray())->where('rfq_suppliers','!=',null));
        } elseif ($type == 'Quote'){
            $rfqs = Quote::with('chatGroupQuote:_id,chat_type,chat_id,group_name')->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')->with('rfq:reference_number,id,company_id')->orWhere('quotes.quote_number', 'like', '%'.$string.'%')->whereNotIn('quotes.status_id', [5,3,4])->where(['quotes.is_deleted' => 0, 'quotes.supplier_id' => $supplier_id])->orderBy('id', 'desc')->get(['quotes.id', 'quote_number', 'rfq_id', 'rfqs.company_id']);
        }
        return $rfqs;
    }

    public function getProductInfo($id,$type){
        if($type == 'Rfq') {
            $productInfo = RfqProduct::where(['rfq_id' => $id, 'is_deleted' => 0])->with(['unit' => function ($u) {
                $u->select(['units.id', 'name']);
            }])->get(['id', 'category', 'sub_category', 'product', 'product_description', 'quantity', 'unit_id']);
        }elseif ($type == 'Quote'){
            $productInfo = $productList =QuoteItem::where('quote_id',$id)->with(['rfqProduct'=>function($r) {
                $r->select(['id','category','sub_category','product','quantity','unit_id'])->where("is_deleted",0)->with('unit:id,name');
            }])->with(['quoteDetails'=>function($d){
                $d->select(['id','final_amount','supplier_id'])->with('supplier:id,name');
            }])->get(['id','quote_id','supplier_id','product_id','rfq_product_id']);
        }
        return $productInfo;
    }
    public function getSupplierNewChatList($type){
        $supplier_id = getSupplierByLoginId(auth()->user()->id);
        if($type=='Rfq'){
            $rfqs = Rfq::with(['chatGroupRfq:_id,chat_type,chat_id,group_name','rfqSuppliers' =>function($q) use ($supplier_id){
                    $q->where('supplier_id', $supplier_id)->select(['supplier_id']);
                }])->where('rfqs.is_deleted', 0)->orderBy('rfqs.id', 'desc')->groupBy('rfqs.id')->get(['rfqs.id','rfqs.reference_number', 'rfqs.company_id']);
           $rfqs = json_decode(collect($rfqs->toArray())->where('rfq_suppliers','!=',null));
        }elseif ($type=='Quote'){
            $supplierID = getSupplierByLoginId(Auth::User()->id);
            $rfqs = Quote::with('chatGroupQuote:_id,chat_type,chat_id,group_name')->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')->with('rfq:reference_number,id,company_id')->whereNotIn('quotes.status_id', [5,3,4])->where(['quotes.is_deleted' => 0, 'quotes.supplier_id' => $supplierID])->orderBy('id', 'desc')->get(['quotes.id', 'quote_number', 'rfq_id', 'rfqs.company_id']);
        }
        return $rfqs;
    }

    /** @ekta
     * @param $agent
     * @param $assignedCategory
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAgentNewChatList($type,$assignedCategory){
        if($type == 'Rfq') {
            /*
            $dataList = Rfq::with('chatGroupRfq:_id,chat_type,chat_id,group_name,company_id')
                ->where(['rfqs.is_deleted' => 0])->orderBy('id', 'desc')->get(['id', 'reference_number', 'company_id']);
            dd($dataList->toArray());
            */
            $dataList = Rfq::with('chatGroupRfq:_id,chat_type,chat_id,group_name,company_id')
                ->where(['rfqs.is_deleted' => 0]);
            //For agent wise category
            $dataList->whereHas('rfqProduct', function($dataList) use($assignedCategory){
                $dataList->whereIn('category_id', $assignedCategory);
            });
            $dataList = $dataList->orderBy('id', 'desc')->get(['id', 'reference_number', 'company_id']);

        }elseif ($type == 'Quote'){
            $dataList = Quote::with('chatGroupQuote:_id,chat_type,chat_id,group_name,company_id')->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')->with('rfq:reference_number,id,company_id')->whereNotIn('quotes.status_id', [5,3,4])->where(['quotes.is_deleted' => 0])->orderBy('id', 'desc')->get(['quotes.id', 'quote_number', 'rfq_id', 'rfqs.company_id']);
        }
        return $dataList;
    }

    public function getSearchAgentNewGroupChatList($string, $type, $assignedCategory){
        if (empty($string)){
            return self::getAgentNewChatList($type, $assignedCategory);
        }
        if($type == 'Rfq') {
            $return = Rfq::with('chatGroupRfq:_id,chat_type,chat_id,group_name')
                ->orWhere('reference_number', 'like', '%' . $string . '%')
                ->where([ 'is_deleted' => 0]);
            //For agent wise category
            $return->whereHas('rfqProduct', function($return) use($assignedCategory){
                $return->whereIn('category_id', $assignedCategory);
            });
            $return = $return->orderBy('id', 'desc')
            ->get(['id', 'reference_number']);
            return $return;
        }elseif ($type == 'Quote') {
            return Quote::with('chatGroupQuote:_id,chat_type,chat_id,group_name')->orWhere('quote_number', 'like', '%' . $string . '%')->with('rfq:reference_number,id')->whereNotIn('quotes.status_id', [5,3,4])->where(['is_deleted' => 0])->orderBy('id', 'desc')->get(['id', 'quote_number', 'rfq_id']);
        }
    }

    public function rfq()
    {
        return $this->belongsTo(Rfq::class, 'chat_id');
    }
    public function groupChatMember()
    {
        return $this->hasOne(GroupChatMember::class, 'group_chat_id');
    }
    public function groupChatMessage()
    {
        return $this->hasMany(GroupChatMessage::class, 'group_chat_id');
    }
    public function quote()
    {
        return $this->belongsTo(Quote::class, 'chat_id');
    }
    public function rfq_product(){
        return $this->belongsTo(RfqProduct::class,'rfq_id','chat_id');
    }

}
