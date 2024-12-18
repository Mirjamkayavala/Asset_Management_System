<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Department;
use App\Models\Location;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(){
        $user = Auth::user();
        $assets = Asset::all();

        // Fetch status counts and set default values if not present
        $statuses = Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalAssets = $assets->count();

        // Extract counts for each status
        // $inStorageAssets = Asset::where('status', 'In_Storage')->get();
        $In_StorageCount = Asset::where('status', 'In Storage')->orWhere('status', 'In_Storage')->count();
        // $In_StorageCount = $statuses->get('In_Storage', 0);
        $BrokenCount = $statuses->get('Broken', 0);
        $NewAssetCount = $statuses->get('New', 0);
        $OldAssetCount = $statuses->get('Old', 0);
        $WrittenOffCount = $statuses->get('WrittenOff', 0);

        return view('dashboard', compact(
            'user', 'assets', 'totalAssets', 
            'In_StorageCount', 'BrokenCount', 
            'NewAssetCount', 'OldAssetCount', 
            'WrittenOffCount'
        ));
    }
}

