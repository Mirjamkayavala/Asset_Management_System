<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the facilities.
     */
    public function index()
    {
        $facilities = Facility::all();
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new facility.
     */
    public function create()
    {
        return view('facilities.create');
    }

    /**
     * Store a newly created facility in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
        ]);

        Facility::create($request->all());

        return redirect()->route('facilities.index')->with('success', 'Facility created successfully.');
    }

    /**
     * Display the specified facility.
     */
    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified facility.
     */
    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified facility in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
        ]);

        $facility->update($request->all());

        return redirect()->route('facilities.index')->with('success', 'Facility updated successfully.');
    }

    /**
     * Remove the specified facility from storage.
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'Facility deleted successfully.');
    }
}
