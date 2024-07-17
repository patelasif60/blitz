<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Console\Command;
use App\Models\Groups;
use App\Models\GroupSupplierDiscountOption;
use DB;
use Carbon\Carbon;
use App\Jobs\GroupExpireBuyerJob;
use App\Jobs\GroupExpireJob;

class GroupExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'groupExpire:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and change group status to expire based different criteria';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $expireStatusId = DB::table('group_status')->where('id', 4)->select('id')->get()->first();

        if($expireStatusId){
            $todayDate = Carbon::now()->toDateString();
            $groupsObj = Groups::where('group_status','<>',$expireStatusId->id)
                // ->where('status','<>',$expireStatusId->id)
                ->whereDate('end_date', '<' ,$todayDate)
                //->whereColumn('achieved_quantity', 'target_quantity')
                ;
            $groupsObjClone = clone $groupsObj;
            $groupIds = $groupsObj->pluck('id')->toArray();
            $groupsObj->update(['group_status' => $expireStatusId->id]);
            // dd($groupIds);
            if(sizeof($groupIds) > 0){

                foreach ($groupIds as $index => $groupId) {
                    $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                        ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                        ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
                        ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
                        ->leftjoin('products', 'groups.product_id', '=', 'products.id')
                        ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
                        ->where('groups.id',$groupId)
                        ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.target_quantity','groups.achieved_quantity','groups.end_date', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','units.name as unit_name']);
                    $groupRanges = GroupSupplierDiscountOption::where('group_id', $groupId)->where('deleted_at', null)->get()->toArray();
                    $url = route('group-details', ['id' => Crypt::encrypt($groupId)]);
                    $shareLinks = getGroupsLinks($groupId);
                    $groupsMailData = [
                        'group' => $groupData[0],
                        'groupRanges' => $groupRanges,
                        'url' => $url,
                        'shareLinks' =>$shareLinks,
                    ];

                    // send mail to admin, supplier and buyer
                    dispatch(new GroupExpireJob($groupsMailData,$groupData[0]->supplier_email)); //mail send admin and supplier
                    dispatch(new GroupExpireBuyerJob($groupId,$url,$shareLinks)); // mail send buyer
                    // send mail to admin, supplier and buyer

                }
            }
        }
        return 'group mail send success';
    }
}
