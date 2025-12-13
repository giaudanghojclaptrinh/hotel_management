<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class ReplyReceived extends Notification
{
    use Queueable;

    protected $reply;
    protected $parent;

    public function __construct($reply)
    {
        $this->reply = $reply;
        $this->parent = $reply->parent;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $senderName = optional($this->reply->user)->name ?: 'Khách';
        $message = sprintf('%s đã trả lời bình luận của bạn: "%s"', $senderName, Str::limit($this->reply->comment, 120));

        return [
            'message' => $message,
            'icon' => 'fa-reply',
            'reply_id' => $this->reply->id,
            'parent_id' => $this->parent->id ?? null,
            'loai_phong_id' => $this->reply->loai_phong_id,
            'sender_id' => $this->reply->user_id,
        ];
    }
}
