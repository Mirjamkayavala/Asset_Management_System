<?php

namespace App\Http\Controllers;

use App\Models\AssetAssignment;
use App\Models\AssetAssignmentHistory;
use Illuminate\Http\Request;
use App\Models\AssetHistory;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;

class AssetAssignmentController extends Controller
{
    public function index()
{
    $asset = Asset::first();
    $assignments = AssetAssignment::with(['user', 'asset'])->paginate(50);
    $history = AssetAssignmentHistory::with('changedBy')->where('asset_id', $asset->id)->get();

    // $asset = Asset::first(); // Replace this with the logic you need

    return view('asset-assignments.index', compact('assignments', 'asset'));
}

    
//     // public function showAssetHistory($assetId)
//     // {
//     //     $asset = Asset::findOrFail($assetId);
//     //     $assetHistory = AssetHistory::where('asset_id', $assetId)->with('user')->latest()->get();

//     //     return view('assets.history', compact('asset', 'assetHistory'));
//     // }

//     public function clear()
//     {
//         AssetHistory::truncate();
//         return redirect()->route('asset-assignments.index')->with('success', 'All asset assignments cleared.');
//     }
    public function assignAsset(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if the asset is already assigned
        $existingAssignment = AssetAssignment::where('asset_id', $request->asset_id)->first();

        if ($existingAssignment) {
            // Record history of the previous assignment
            AssetAssignmentHistory::create([
                'asset_id' => $existingAssignment->asset_id,
                'user_id' => $existingAssignment->user_id,
                'changed_by' => Auth::id(),
                'change_type' => 'assignment',
                'changes' => ['previous_user' => $existingAssignment->user_id, 'new_user' => $request->user_id]
            ]);

            // Update the current assignment
            $existingAssignment->update([
                'user_id' => $request->user_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
            ]);
        } else {
            // Create new assignment
            AssetAssignment::create([
                'asset_id' => $request->asset_id,
                'user_id' => $request->user_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Asset assigned successfully']);
    }

    public function updateAsset(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'updates' => 'required|array'
        ]);

        $existingAssignment = AssetAssignment::findOrFail($id);

        $changes = [];
        foreach ($request->updates as $key => $value) {
            if ($existingAssignment->$key != $value) {
                $changes[$key] = [
                    'old' => $existingAssignment->$key,
                    'new' => $value,
                ];
                $existingAssignment->$key = $value;
            }
        }

        $existingAssignment->save();

        AssetAssignmentHistory::create([
            'asset_id' => $request->asset_id,
            'user_id' => $existingAssignment->user_id,
            'changed_by' => Auth::id(),
            'change_type' => 'update',
            'changes' => $changes
        ]);

        return response()->json(['message' => 'Asset updated successfully']);
    }
    // public function clear()
    // {
    //     AssetHistory::truncate();
    //     return redirect()->route('asset-assignments.index')->with('success', 'All asset assignments cleared.');
    // }

    

}
