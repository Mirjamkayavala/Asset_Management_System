<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Region::class);
        $regions = Region::paginate(10);
        return view('regions.index', compact ('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Region::class);
        return view('regions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request)
    {
        $this->authorize('create', Region::class);
        $validatedData = $request->validated();

        Region::create($validatedData);

        return redirect()->route('regions.index')
                        ->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        $this->authorize('edit', Region::class);
        return view('regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        $this->authorize('update', $region);
        $request->validate([
            'region_name' => 'required|string|max:255',
            'zipcode' => 'required|string|max:15',
        ]);

        $region->update($request->all());
        
    

        return redirect()->route('regions.index')
                         ->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        
        $this->authorize('delete', $region);
        $region->delete();

        return redirect()->route('regions.index')
                         ->with('success', 'Region deleted successfully.');
    }
}
