<?php
namespace App\Http\Controllers;

use App\Models\AssetCategory;
use App\Http\Requests\StoreAssetCategoryRequest;
use App\Http\Requests\UpdateAssetCategoryRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class AssetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assetCategories = AssetCategory::paginate(10);
        $users = User::all();
        return view('asset_categories.index', compact('assetCategories', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', AssetCategory::class);
        $users = User::all(); 
        return view('asset_categories.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetCategoryRequest $request)
    {
        // Explicitly set category_code to null if it's not provided
        $data = $request->all();
        $data['category_code'] = $request->category_code ?? null;

        AssetCategory::create($data);

        return redirect()->route('asset_categories.index')
            ->with('success', 'Asset category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetCategory $assetCategory)
    {
        $this->authorize('edit', AssetCategory::class);

        $category = $assetCategory;
        $users = User::all();
        return view('asset_categories.edit', compact('category', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetCategoryRequest $request, AssetCategory $assetCategory)
    {
        $this->authorize('update', $assetCategory);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_code' => 'nullable|string|max:50|unique:asset_categories,category_code,' . $assetCategory->id . ',id',
        ]);

        // Explicitly set category_code to null if it's not provided
        $data = $request->all();
        $data['category_code'] = $request->category_code ?? null;

        $assetCategory->update($data);

        return redirect()->route('asset_categories.index')
            ->with('success', 'Asset category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetCategory $assetCategory)
    {
        $this->authorize('delete', $assetCategory);
        $assetCategory->delete();

        return redirect()->route('asset_categories.index')
            ->with('success', 'Asset category deleted successfully.');
    }
}
