<?php

namespace App\Mail;

use App\Models\Groups;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupRangeAchievedBuyer extends Mailable
{
    use Queueable, SerializesModels;
    public $group;
    public $BuyerName;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Groups $group,$BuyerName,$url)
    {
        $this->group = $group;
        $this->BuyerName = $BuyerName;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $groupno = isset($this->group->group_number) ? $this->group->group_number : '';
        return $this->subject($groupno. ' Group Range Achieved')
            ->markdown('emails.group.groupRangeAchievedBuyer')
            ->with('group', $this->group)
            ->with('BuyerName', $this->BuyerName)
            ->with('url', $this->url)
            ->with('productDetailsMultiple', $this->group->productDetailsMultiple()->get());
    }
}
