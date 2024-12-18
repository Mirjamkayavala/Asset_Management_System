<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use App\Jobs\UploadInsuranceDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class InsuranceController extends Controller
{
    public function index()
    {

        if (Gate::denies('viewAny', Insurance::class)) {
            abort(403, 'Unauthorized');
        }

         // Fetch insurance records in descending order by 'created_at'
        $insurances = Insurance::with('asset', 'user')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $assets = Asset::all();
        $users = User::all();

        return view('insurances.index', compact('insurances', 'assets', 'users'));
    }

    public function create()
    {
        
        $this->authorize('create', Insurance::class);

        $assets = Asset::all();
        $users = User::all();
        return view('insurances.create', compact('assets', 'users'));
    }

    public function store(StoreInsuranceRequest $request)
    {
        $validated = $request->validated();

        // Create the insurance record without the file path
        $insurance = Insurance::create($validated);

        // Store the file and dispatch the job
        if ($request->hasFile('insurance_document')) {
            $filePath = $request->file('insurance_document')->store('insurance_document');
            UploadInsuranceDocument::dispatch($filePath, $insurance->id);
        }

        return redirect()->route('insurances.index')->with('success', 'Insurance claim created successfully.');
    }


    public function show(Insurance $insurance)
    {
        $this->authorize('view', $insurance);
        return view('insurances.show', compact('insurance'));
    }

    public function edit(Insurance $insurance)
    {
        $this->authorize('edit', Insurance::class);

        $assets = Asset::all();
        $users = User::all();
        return view('insurances.edit', compact('insurance', 'assets', 'users'));
    }

    public function update(UpdateInsuranceRequest $request, $id)
    {
        $insurance = Insurance::findOrFail($id);
        
        // Validate request
        $validated = $request->validated();

        // Check if a new document is uploaded
        if ($request->hasFile('insurance_document')) {
            
            $filePath = $request->file('insurance_document')->store('insurance_document');
            Log::info($filePath);
            
            //delete the old document file
            if ($insurance->insurance_document) {
                Storage::delete($insurance->insurance_document);
            }

            // Update the file path
            $validated['insurance_document'] = $filePath;
        } else {
            // Retain the existing document if no new file is uploaded
            $validated['insurance_document'] = $insurance->insurance_document;
        }

        // Update the insurance record
        $insurance->update($validated);

        return redirect()->route('insurances.index')->with('success', 'Insurance record updated successfully.');
    }



    public function destroy(Insurance $insurance)
    {
        $this->authorize('delete', $insurance);
        // Delete the associated file if exists
        if ($insurance->insurance_documents) {
            Storage::delete($insurance->insurance_documents);
        }

        $insurance->delete();

        return redirect()->route('insurances.index')->with('success', 'Insurance claim deleted successfully.');
    }

    // public function viewInsuranceDoc($filename){
 
    public function viewInsuranceDoc(Insurance $insurance){
        // dd($filename);
        // $filepath ='insurance_document'.$filename;
        // return Storage::path($insurance->insurance_document);

        // return Storage::download($insurance->insurance_document);
        return response()->file(storage_path('app/' . $insurance->insurance_document));

    }
}
