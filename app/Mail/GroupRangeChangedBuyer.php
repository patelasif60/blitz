<?php

namespace App\Mail;

use App\Models\Groups;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupRangeChangedBuyer extends Mailable
{
    use Queueable, SerializesModels;
    public $group;
    public $BuyerName;
    public $url;
    public $shareLinks;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Groups $group,$BuyerName,$url,$shareLinks)
    {
        $this->group = $group;
        $this->BuyerName = $BuyerName;
        $this->url = $url;
        $this->shareLinks = $shareLinks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $groupno = isset($this->group->group_number) ? $this->group->group_number : '';
        return $this->subject($groupno. ' Group Range Changed')
            ->markdown('emails.group.groupRangeChangedBuyer')
            ->with('group', $this->group)
            ->with('BuyerName', $this->BuyerName)
            ->with('url', $this->url)
            ->with('shareLinks', $this->shareLinks)
            ->with('productDetailsMultiple', $this->group->productDetailsMultiple()->get());
    }
}
