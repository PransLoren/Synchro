<?php

// App\Notifications\EmailAcceptInvitation.php
namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailAcceptInvitationNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Correctly generate the invitation URL with the project ID and token
        $invitationUrl = url('/projects/' . $this->project->id . '/invitation/' . $this->token . '/accept');

        return (new MailMessage)
            ->subject('Invitation to join a project')
            ->line('You have been invited to join the project: ' . $this->project->name)
            ->action('Accept Invitation', $invitationUrl);
    }


}
