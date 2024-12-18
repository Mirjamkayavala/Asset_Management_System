<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AssetDeleted extends Notification implements ShouldQueue
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
                    ->line('A new asset has been deleted.')
                    ->action('Restore Asset', route('assets.deleted', ['id' => $this->asset->id]))
                    ->line('Thank you for using our IT Inventory Management System!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new asset has been deleted: ' . $this->asset->asset_name,
            'asset_id' => $this->asset->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'A new asset has been deleted: ' . $this->asset->asset_name,
            'asset_id' => $this->asset->id,
        ]);
    }
}
