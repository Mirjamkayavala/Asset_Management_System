<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetHistory;
use Illuminate\Support\Facades\Auth;

class AssetAssignmentController extends Controller
{
    public function index()
    {
        $assignments = AssetHistory::with(['user', 'asset'])->paginate(50);

        return view('asset-assignments.index', compact('assignments'));

    }

    
    // public function showAssetHistory($assetId)
    // {
    //     $asset = Asset::findOrFail($assetId);
    //     $assetHistory = AssetHistory::where('asset_id', $assetId)->with('user')->latest()->get();

    //     return view('assets.history', compact('asset', 'assetHistory'));
    // }

    public function clear()
    {
        AssetHistory::truncate();
        return redirect()->route('asset-assignments.index')->with('success', 'All asset assignments cleared.');
    }


    

}
