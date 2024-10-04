<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AssetCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $asset;

    public function __construct($asset)
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
            ->subject('New Asset Added')
            ->line('A new asset has been added to the system.')
            ->action('View Asset', route('assets.show', $this->asset->id))
            ->line('Thank you for using our application!');
            
       
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new asset has been created: ' . $this->asset->asset_name,
            'asset_id' => $this->asset->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'A new asset has been created: ' . $this->asset->asset_name,
            'asset_id' => $this->asset->id,
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'asset_number' => $this->asset->asset_number ?? 'N/A',
            'asset_name' => $this->asset->asset_name ?? 'N/A',
        ];
    }
}
