<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupLeaveBuyer extends Mailable
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

        return $this->subject($groupno. ' Group Leave')
            ->markdown('emails.group.groupLeaveBuyer')
            ->with('group', $this->group);
    }
}
