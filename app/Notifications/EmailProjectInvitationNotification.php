<?php

namespace App\Notifications;

use App\Models\ProjectModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailProjectInvitationNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(ProjectModel $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Invitation to join a project')
            ->line('You have been invited to join the project: ' . $this->project->name)
            ->action('Accept Invitation', url('/projects/' . $this->project->id . '/accept-invitation'));
    }
}
