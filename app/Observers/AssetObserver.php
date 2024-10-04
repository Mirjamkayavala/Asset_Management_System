<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\User;
use App\Models\AssetHistory;
use App\Notifications\AssetAdded;
use App\Notifications\AssetDeleted;
use App\Notifications\AssetUpdated;

class AssetObserver
{
    /**
     * Handle the Asset "created" event.
     */
    protected $deletedAssetData = null;
    public function created(Asset $asset): void
    {
        //
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'created',
            'description' => 'Asset created: ' . json_encode($asset->toArray()),
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Handle the Asset "updated" event.
     */
    public function updated(Asset $asset): void
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'updated',
            'description' => 'Asset updated: ' . json_encode($asset->getChanges()),
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Handle the Asset "deleted" event.
     */
    public function deleted(Asset $asset): void
    {
        // AssetHistory::create([
        //     'asset_id' => $asset->id,
        //     'action' => 'deleted',
        //     'description' => 'Asset deleted: ' . json_encode($asset->toArray()),
        //     'user_id' => auth()->user()->id,
        // ]);
    }

    /**
     * Handle the Asset "restored" event.
     */
    public function restored(Asset $asset): void
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'restored',
            'description' => 'Asset restored: ' . json_encode($asset->toArray()),
            'user_id' => auth()->user()->id, //global variable
        ]);
    }

    /**
     * Handle the Asset "force deleted" event.
     */
    public function forceDeleted(Asset $asset): void
    {
        // AssetHistory::create([
        //     'asset_id' => $asset->id,
        //     'action' => 'force deleted',
        //     'description' => 'Asset force deleted: ' . json_encode($asset->toArray()),
        //     'user_id' => auth()->user()->id,
        // ]);
    }
}
