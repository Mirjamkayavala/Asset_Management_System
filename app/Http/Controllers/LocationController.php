<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\location;
use App\Models\Region;
use App\Http\Requests\StorelocationRequest;
use App\Http\Requests\UpdatelocationRequest;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Location::class);
        $users = User::all();
        $locations = Location::with('region')->paginate(50);
        // $locations = Location::with('region')->get();
        $regions = Region::all();
        return view('locations.index', compact('locations', 'users', 'regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', $location);
        $users = User::all();
        $assets = Asset::all();
        $regions = Region::all();
        return view('locations.create', compact('users','assets', 'regions') );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorelocationRequest $request)
    {

       // Authorize the 'create' action without needing an instance of the model
        $this->authorize('create', Location::class);

        // Create the location using the validated data
        Location::create($request->all());

       
        // dd($request->all());
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');

        
    }

    /**
     * Display the specified resource.
     */
    public function show(location $location)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(location $location)
    {
        $this->authorize('edit', $location);
        $location = Location::findOrFail($id);
        $users = User::all();
        $regions = Region::all();
        return view('locations.edit', compact('location', 'users', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatelocationRequest $request, location $location)
    {

        $this->authorize('update', $location);
        // $request->validate([
        //     'location_name' => 'required|string|max:255',
        //     'contact_information' => 'required|string|max:255',
        //     'region' => 'required|string|max:255',
           
        // ]);

        
        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(location $location)
    {
        $this->authorize('delete', $location);
        // $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
