<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemovedBuyerFromGroup extends Mailable
{
    use Queueable, SerializesModels;
    public $group;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $groupno = isset($this->group['group']->group_number) ? $this->group['group']->group_number : '';

        return $this->subject($groupno. ' Removed From Group')
            ->markdown('emails.group.removedBuyerFromGroup')
            ->with('group', $this->group);
    }
}
