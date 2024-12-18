<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssetDeletionApprovalRequested extends Notification implements ShouldQueue
{
    use Queueable;

    protected $asset;

    public function __construct($asset)
    {
        $this->asset = $asset;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('An asset deletion requires your approval.')
                    ->action('View Asset', url('/assets/' . $this->asset->id))
                    ->line('Please review and approve the deletion request.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'An asset deletion request for approval.',
            'asset_id' => $this->asset->id,
        ];
    }
}
