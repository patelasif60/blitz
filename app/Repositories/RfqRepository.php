<?php

namespace App\Repositories;
use App\Models\Rfq;
use App\Models\User;
use App\Models\UserRfq;
use App\Models\PreferredSupplier;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;
use App\Models\BuyerNotification;
use App\Models\Quote;
use App\Models\ApprovalRejectReason;

class RfqRepository extends BaseRepository
{
    // get all rfq pagination wise
    public function getRfqList($perPage,$page,$favrfq,$searchedData,$rfqstatus)
    {
        $authUser = Auth::user();
        $companyData =  $authUser->company;
        $isOwner = User::checkCompanyOwner();
        $default_company = $authUser->default_company;
            
        $userRfq =UserRfq::with('rfq','userrfqName','rfq.rfqProducts','rfq.rfqStatus','rfq.rfqUser', 'rfq.getQuoteMetaData','rfq.rfqProducts.unit','rfq.rfqAttachment','rfq.group','rfq.quote','rfq.quote.quoteStatus','rfq.quote.quoteItems','rfq.quote.supplier','rfq.quote.approvalRejectReason','rfq.quote.order','rfq.City','rfq.State','rfq.quote.getUserQuoteFeedback','rfq.quote.approvalRejectReason');

        $userRfq = $userRfq->whereHas("rfq",function($q){
             
            $q->whereHas("rfqProducts",function($q){
                $q->has('unit');
            });
            $q->where("is_deleted",0);
        });
        
        if ($authUser->hasPermissionTo('list-all buyer rfqs') || $isOwner == true) {
            $userRfq =  $userRfq->whereHas("rfq",function($q) use($default_company){$q->where("company_id",$default_company);});
        }else{
            $userRfq = $userRfq->where('user_rfqs.user_id', $authUser->id)->whereHas("rfq",function($q) use($default_company){$q->where("company_id",$default_company);});
        }

        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer preferred supplier')){
            $allSuppliersIds = PreferredSupplier::where('company_id',$default_company)->get()->pluck(['supplier_id']);
        }else {
            $allSuppliersIds = PreferredSupplier::where('user_id',$authUser->id)->where('company_id', $default_company)->get()->pluck(['supplier_id']);
        }

        if($favrfq > 0){
            $userRfq = $userRfq->whereHas("rfq",function($q) use($favrfq){$q->where("is_favourite",$favrfq);});
        }
         if (!empty($rfqstatus) && trim($rfqstatus) != 'all' ) {
            $userRfq = $userRfq->whereHas("rfq",function($q) use($rfqstatus){$q->where("status_id",$rfqstatus);});
        }
        if (!empty($searchedData)) {
            $userRfq = $userRfq->Where(function($q) use($searchedData){
                $q->WhereHas('rfq', function($userRfq) use($searchedData){
                    $userRfq->where('id', 'LIKE',"%$searchedData%");
                });
                $q->orWhereHas('rfq.quote', function($userRfq) use($searchedData){
                    $userRfq->where('id', 'LIKE',"%$searchedData%");
                });
            });
        }

        $buyerRfqCountWhere = ['user_id' => $authUser->id, 'user_activity' => 'RFQ Created', 'notification_type' => 'rfq', 'side_count_show' => 0];
        BuyerNotification::where($buyerRfqCountWhere)->update(['side_count_show' => 1]);
        $buyerQuoteCountWhere = ['user_id' =>$authUser->id, 'user_activity' => 'Quote Create', 'notification_type' => 'quote', 'side_count_show' => 0];
        BuyerNotification::where($buyerQuoteCountWhere)->update(['side_count_show' => 1]);
        $dots = getUnreadMessageAlert();


        $total = clone $userRfq->groupBy('rfq_id');
        $totalRecord = count($total->get()) ;

        $rfqs = $userRfq->orderBy('rfq_id', 'desc')
        ->groupBy('rfq_id')
        ->paginate($perPage, ['user_rfqs.*','user_rfqs.user_id as created_by','rfq_id'], null, $page);

        $rfqs->totalRecord = $totalRecord;
        if(count($rfqs)==10)
        {
           $rfqs->currentRecord = $perPage*$page;
        }
        else{
           $rfqs->currentRecord = $perPage* ($page - 1) + count($rfqs);
        }
        $approvalUsers = collect();
        $isCompanyOwner = User::checkOwnerByCompanyId($authUser->default_company);
        $approvalUsersByCompany = User::whereJsonContains('assigned_companies',$authUser->default_company)->get();
         $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
        foreach($approvalUsersByCompany as $cmpUser) {
            $permissions = getRolePermissionAttribute($cmpUser->id ?? null)['permissions'];                     //Get Role & permission by user id

            if (!empty($permissions)) {
                if (in_array($approverPermissionId, $permissions)) {
                    $approvalUsers->push($cmpUser);
                }
            }
        }

          /*********begin: set permissions based on custom role.**************/
        if (($authUser->hasPermissionTo('toggle buyer approval configurations') == true) || $isCompanyOwner == true) {
            $appProcessValue['approval_process'] = 1;
        } else {
            $appProcessValue['approval_process'] = 0;
        }
        /*********end: set permissions based on custom role.**************/
        $setting = getSettingValueByKey('slug_prefix');

        $approvalReject = ApprovalRejectReason::with('getApprovalRejectReasons')->get();
        
        return $this->getRfqWisedata($rfqs,$approvalUsers,$companyData,$allSuppliersIds,$appProcessValue,$isOwner,$setting,$approvalReject);
    }
    public function getRfqWisedata($rfqs,$approvalUsers,$companyData,$allSuppliersIds,$appProcessValue,$isOwner,$setting,$approvalReject)
    {
        $authUser = Auth::user();
        $companyData =  $authUser->company;
        $isOwner = User::checkCompanyOwner();
        $default_company = $authUser->default_company;
        $query = $rfqs->pluck('rfq')->where("company_id",$default_company);
        if ($isOwner == true || $authUser->hasPermissionTo('list-all buyer quotes')) {
            $rfqId = $query->pluck('id')->toArray();
        }elseif($authUser->hasPermissionTo('publish buyer quotes')){
            $rfqId = $rfqs->where('user_id',$authUser->id)->pluck('rfq')->where("company_id",$default_company)->pluck('id')->toArray();
        }else{
            $rfqId = $rfqs->pluck('rfq')->where("company_id",'<>',$default_company)->pluck('id')->toArray();
        }

        foreach ($rfqs as $rfq) {
            $rfq->id = $rfq->rfq->id; 
            $rfq->firstname = $rfq->rfq->firstname;
            $rfq->lastname  = $rfq->rfq->lastname;
            $rfq->email  = $rfq->rfq->email;
            $rfq->mobile  = $rfq->rfq->mobile;
            $rfq->termsconditions_file  = $rfq->rfq->termsconditions_file;
            $rfq->created_at  = $rfq->rfq->created_at;
            $rfq->reference_number = $rfq->rfq->reference_number;
            $rfq->address_line_1 = $rfq->rfq->address_line_1; 
            $rfq->address_line_2 = $rfq->rfq->address_line_2; 
            $rfq->sub_district = $rfq->rfq->sub_district; 
            $rfq->district = $rfq->rfq->district;
            $rfq->state_id = $rfq->rfq->state_id; 
            $rfq->city_id = $rfq->rfq->city_id; 
            $rfq->pincode = $rfq->rfq->pincode;
            $rfq->rental_forklift =$rfq->rfq->rental_forklift;
            $rfq->unloading_services =$rfq->rfq->unloading_services;
            $rfq->is_require_credit = $rfq->rfq->is_require_credit;
            $rfq->attached_document = $rfq->rfq->attached_document;
            $rfq->status_id = $rfq->rfq->status_id;
            $rfq->groupId = $rfq->rfq->group_id;
            $rfq->group_id = $rfq->rfq->group_id;
            $rfq->groupName = $rfq->rfq->name;
            $rfq->company_id = $rfq->rfq->company_id;
            $rfq->payment_type = $rfq->rfq->payment_type;
            $rfq->credit_days = $rfq->rfq->credit_days;
            $rfq->is_favourite = $rfq->rfq->is_favourite; 
            $rfq->category =$rfq->rfq->rfqProducts->first()->category; 
            $rfq->sub_category =$rfq->rfq->rfqProducts->first()->sub_category; 
            $rfq->product_name =$rfq->rfq->rfqProducts->first()->product;
            $rfq->product_description =$rfq->rfq->rfqProducts->first()->product_description;
            $rfq->quantity =$rfq->rfq->rfqProducts->first()->quantity;
            $rfq->expected_date =$rfq->rfq->rfqProducts->first()->expected_date;
            $rfq->comment =$rfq->rfq->rfqProducts->first()->comment;
            $rfq->status_name =$rfq->rfq->rfqStatus->name;
            $rfq->unit_name = $rfq->rfq->rfqProducts->first()->unit->name;
            $rfq->unit_id = $rfq->rfq->rfqProducts->first()->unit_id;
            $rfq->city = $rfq->rfq->city_id > 0 ? $rfq->rfq->City->name : $rfq->city;
            $rfq->state = $rfq->rfq->state_id > 0 ? $rfq->rfq->State->name : $rfq->state;
            $rfq->created_by = $rfq->userrfqName->full_name;

            $rfq->quotes =  $rfq->rfq->quote->where('status_id', '<>', 5)->whereIn('rfq_id',$rfqId)->sortByDEsc('quotes.id')->groupBy('quotes.id')->flatten();
            $rfq->compareQuote = $rfq->rfq->quote->where('status_id', '<>', 5)->whereIn('rfq_id',$rfqId)->sortByDEsc('quotes.id')->groupBy('quotes.id')->flatten();
            $rfq->all_products = $rfq->rfq->rfqProducts;
            $rfq->rfq_attachments = $rfq->rfq->rfqAttachment;
            if($rfq->quotes){
                foreach($rfq->quotes as $val){
                    $val->quotes_id = $val->id;
                    $val->supplier_name = $val->supplier->contact_person_name;
                    $val->supplier_company_name = $val->supplier->name;
                    $val->supplier_profile_username = $val->supplier->profile_username;
                    $val->status_name = $rfq->status_name;
                    $val->rfq_reference_number = $rfq->reference_number;
                    $val->product_description = $rfq->product_description;
                    $val->product = $rfq->product_name;
                    $val->rfqId = $rfq->id;
                    $val->quote_status_name =$val->quoteStatus->name;
                    $val->orderPlaced = $val->order && count($val->order->toArray()) > 0 ? true : false;
                    $val->certificate_attachment =count(array_filter($val->quoteItems->pluck('certificate')->toArray())) ?? 0;
                    $val->rfqIdArray =  $val->quoteItems->pluck('rfq_product_id')->toArray();
                    $val->number_of_products = count($val->quoteItems->toArray());
                    
                    $totalData = $val->getUserQuoteFeedback;

                    $acceptedFeedback = $totalData->where('user_quote_feedbacks.feedback',1)->count();
                    $rejectedFeedback = $totalData->where('user_quote_feedbacks.feedback',2)->count();
                    $val->percCount = $totalData->count() == 0 ? 0 : number_format($acceptedFeedback / $totalData->count() * 100, 0) . '%';
                    $val->rejectPercCount = $totalData->count() == 0 ? 0 : number_format($rejectedFeedback / $totalData->count() * 100, 0) . '%';



                    if($val->quoteItems)
                    {
                        foreach($val->quoteItems as $quoteitemsVal){
                            $val->min_delivery_days = $quoteitemsVal->min_delivery_days;
                            $val->max_delivery_days = $quoteitemsVal->max_delivery_days;
                            $val->logistic_check = $quoteitemsVal->logistic_check;
                            $val->logistic_provided =$quoteitemsVal->logistic_provided;
                        }
                    }
                    $val->userFeedCount = $acceptedFeedback + $rejectedFeedback;
                    $val->userData = $totalData;
                    $val->totalUser = $totalData->count();
                    $val->accepted = $acceptedFeedback;
                    $val->rejected = $rejectedFeedback;
                    $rfq->compareQuote = $rfq->quotes->sortByDEsc('number_of_products')
                       ->sortBy('final_amount')
                       ->sortBy('max_delivery_days');  
                
                    
                }   
            }
            $rfq->countConfigUsers = count($approvalUsers);
            $rfq->companyData = $companyData;
            $rfq->allSuppliersIds =$allSuppliersIds;
            $rfq->processValue = $appProcessValue;
            $rfq->approvalProcess=$appProcessValue['approval_process'];
            $rfq->isAuthUser = $isOwner;
            $rfq->approvalRejectReason = $approvalReject;
            $rfq->setting =$setting; 
        }
        return $rfqs;
    }
}