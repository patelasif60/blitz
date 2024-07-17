<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupCreateBuyer extends Mailable
{
    use Queueable, SerializesModels;
    public $group;
    public $buyer_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($group,$buyer_name)
    {
        $this->group = $group;
        $this->buyer_name = $buyer_name;//
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $groupno = isset($this->group['group']->group_number) ? $this->group['group']->group_number : '';

        return $this->subject($groupno. ' Group Create')
            ->markdown('emails.group.groupCreateBuyer')
            ->with('group', $this->group)
            ->with('buyer_name', $this->buyer_name);
    }
}
