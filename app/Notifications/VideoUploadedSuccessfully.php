<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class VideoUploadedSuccessfully extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Model $post, private readonly bool $status){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("Video Status")
                    ->view('mail.master', [
                        'name'         => $notifiable->name,
                        '_message'     =>
                            $this->status
                                ? 'Your Video Uploaded Successfully For Post ' . $this->post->title
                                : 'Failed To Upload Video Check Vimeo Free Space Or Limit',
                        'url'          => $this->status ? route('posts.show', $this->post->id) : null,
                        'url_name'     => 'Check Now',
                        'message2'     => 'Thank you for using our application!',
                    ]);
    }

}
