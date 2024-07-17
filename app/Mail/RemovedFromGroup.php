<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemovedFromGroup extends Mailable
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

        return $this->subject($groupno. ' Removed From Group')
            ->markdown('emails.group.removedFromGroup')
            ->with('group', $this->group)
            ->with('userType', $this->userType);
    }
}
