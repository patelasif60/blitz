<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailForChatUnreadMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $chatMessages;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($chatMessages)
    {
        $this->chatMessages = $chatMessages;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $group_name = $this->chatMessages['chat_group']['group_name'];
        return $this->subject( $group_name.' - Pesan Masuk Pada blitznet')
            ->markdown('emails.sendChatUnreadMessage')
            ->with('chatMessages', $this->chatMessages);
    }
}
