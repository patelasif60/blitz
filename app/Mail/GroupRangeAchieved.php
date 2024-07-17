<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupRangeAchieved extends Mailable
{
    use Queueable, SerializesModels;
    public $group;
    public $userType;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($group,$userType)
    {
        $this->group = $group;
        $this->userType = $userType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $groupno = isset($this->group['group']->group_number) ? $this->group['group']->group_number : '';

        return $this->subject($groupno. ' Group Range Achieved')
            ->markdown('emails.group.groupRangeAchieved')
            ->with('group', $this->group)
            ->with('userType', $this->userType);

        /*
          //mail send supplier and admin and buyer for group close and expire
        $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
            ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
            ->where('groups.id',$request->id)
            ->get(['groups.id', 'groups.name', 'groups.group_number','groups.target_quantity','groups.achieved_quantity', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email']);
        $groupRanges = GroupSupplierDiscountOption::where('group_id', $group->id)->where('deleted_at', null)->get()->toArray();
        $url = route('group-details', ['id' => Crypt::encrypt($request->id)]);
        $groupsMailData = [
            'group' => $groupData[0],
            'groupRanges' => $groupRanges,
            'url' => $url,
        ];


        dispatch(new GroupRangeAchievedJob($groupsMailData,$groupData[0]->supplier_email)); //mail send admin and supplier
        dispatch(new GroupRangeChangedBuyerJob($request->id,$url)); // mail send buyer


        //end mail
         */
    }
}
