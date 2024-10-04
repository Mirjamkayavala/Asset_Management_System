<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Asset;

class AssetUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $asset;

    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new asset has been updated.')
                    ->action('View Asset', url('/assets/' . $this->asset->id))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new asset has been updated: ' . $this->asset->asset_name,
            'asset_id' => $this->asset->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'A new asset has been updated: ' . $this->asset->asset_name,
            'asset_id' => $this->asset->id,
        ]);
    }
}
