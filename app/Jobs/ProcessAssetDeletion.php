<?
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Asset;
use App\Models\User;
use App\Notifications\AssetDeleted;
use Illuminate\Support\Facades\Notification;

class ProcessAssetDeletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;

    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    public function handle()
    {
        // Delete the related records in asset_history
        $this->asset->asset_history()->delete();

        // Delete the asset from the database
        $this->asset->delete();

        // Send notification to users about the deletion
        $users = User::all();
        Notification::send($users, new AssetDeleted($this->asset));
    }
}
