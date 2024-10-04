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
        $statuses = Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalAssets = Asset::count();
        $In_UseCount = Asset::where('status', 'In use')->count();
        $In_StorageCount = Asset::where('status', 'In Storage')->count();
        $BrokenCount = Asset::where('status', 'Broken')->count();
        $WrittenOffCount = Asset::where('status', 'Written Off')->count();
        $NewAssetCount = Asset::where('status', 'New')->count();
        $OldAssetCount = Asset::where('status', 'Old')->count();

        $totalAssets = $In_UseCount + $In_StorageCount + $BrokenCount + $WrittenOffCount + $NewAssetCount + $OldAssetCount;

        

        return view('dashboard', compact('user', 'In_UseCount', 'In_StorageCount', 'BrokenCount', 'WrittenOffCount', 'totalAssets','assets', 'NewAssetCount', 'OldAssetCount', 'assets'));
    }
}
