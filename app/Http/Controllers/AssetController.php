<?php

namespace App\Http\Controllers;

use Excel;
use App\Models\User;
use App\Models\Asset;
use App\Models\Vendor;
use App\Models\Department;
// use App\Models\Insurance;
use App\Models\Location;
use App\Models\AuditTrail;
use App\Models\ImportFile;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use App\Models\AssetHistory;
use App\Imports\AssetsImport;
use App\Imports\AssetTempImport;
use App\Jobs\ProcessImportedAssets;
use Illuminate\Validation\Rule;
use App\Jobs\ProcessAssetDeletion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AssetCreated;
use App\Notifications\AssetUpdated;
use App\Notifications\AssetDeleted;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Support\Facades\Notification;


class AssetController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::denies('viewAny', Asset::class)) {
            abort(403, 'Unauthorized');
        }

        
        $paginatedAssets = Asset::with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(50);

        // Group the assets by user
        $groupedAssets = $paginatedAssets->getCollection()->groupBy(function($assets) {
            return $assets->user->name ?? 'Unassigned';
        });

        $data = DB::table('assets')->orderBy('asset_number', 'DESC')
            ->get();
    
        // $load_temp_assets = DB::table('load_temp_assets')->get();


        $users = User::all();
        $vendors = Vendor::all();
        $departments = Department::all();
        $assetCategories = AssetCategory::all();
        $locations = Location::all();
       
        // $insurances = Insurance::all();

        $In_UseCount = Asset::where('status', 'In Use')->count();
        $In_StorageCount = Asset::where('status', 'In Storage')->count();
        $BrokenCount = Asset::where('status', 'Broken')->count();
        $WrittenOffCount = Asset::where('status', 'Written Off')->count();
        $NewCount = Asset::where('status', 'New')->count();
        $OldCount = Asset::where('status', 'Old')->count();

        return view('assets.index', compact('paginatedAssets', 'groupedAssets', 'users', 'In_UseCount', 'In_StorageCount', 'BrokenCount', 'WrittenOffCount', 'vendors', 'assetCategories', 'departments', 'NewCount', 'OldCount', 'data', 'locations'));
    }

    public function import(Request $request)
{
    try {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        // Store the uploaded file temporarily
        $file = $request->file('file')->store('temp'); // Stores in storage/app/temp folder
        $filePath = storage_path('app/' . $file);

        // Use the stored file path in the import function
        Excel::import(new AssetTempImport, $filePath);

        // Fetch the imported data from the temp table
        $tempAssets = DB::table('load_temp_assets')->get();

        foreach ($tempAssets as $tempAsset) {
            // Check if the serial number already exists in the assets table
            $exists = Asset::where('serial_number', $tempAsset->serial_number)
                            ->orWhere('asset_number', $tempAsset->asset_number)
                            ->exists();
            
            if (!$exists) {
                // Convert Excel serial date to MySQL date format
                $convertedDate = \Carbon\Carbon::createFromFormat('Y-m-d', gmdate("Y-m-d", ($tempAsset->date - 25569) * 86400))->toDateString();
                
                // Fetch user_id for current_user
                $currentUserId = DB::table('users')->where('name', $tempAsset->current_user)->value('id');
                
                // Fetch user_id for previous_user
                $previousUserId = DB::table('users')->where('name', $tempAsset->previous_user)->value('id');
                
                // Insert data into the assets table
                Asset::create([
                    'make'            => $tempAsset->make,
                    'model'           => $tempAsset->model,
                    'category'        => $tempAsset->category,
                    'serial_number'   => $tempAsset->serial_number,
                    'asset_number'    => $tempAsset->asset_number,
                    'date'            => $convertedDate,
                    'vendor'          => $tempAsset->vendor,
                    'previous_user_id'=> $previousUserId,
                    'user_id'         => $currentUserId,
                    'status'          => $tempAsset->status,
                ]);
            } else {
                // If asset already exists, log and continue
                Log::warning('Duplicate entry detected: Serial Number: ' . $tempAsset->serial_number . ' or Asset Number: ' . $tempAsset->asset_number);
                continue;
            }
        }

        // Remove all records from the load_temp_assets table after successful import
        DB::table('load_temp_assets')->truncate();

        return redirect()->route('assets.index')->with('success', 'File successfully imported');
        
    } catch (\Exception $e) {
        // Log the actual error
        Log::error('Import error: ' . $e->getMessage());

        // Return a generic error message to the front end
        return redirect()->back()->with('error', 'Failed to import file. Please check your data and try again.');
    }
}








    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (Gate::denies('create', Asset::class)) {
            abort(403, 'Unauthorized');
        }

        
        Gate::authorize('create', Asset::class);

        $users = User::all();
        
        $vendors = Vendor::all();
        
        $assetCategories = AssetCategory::all();
        
        // $insurances = Insurance::all();
        $locations = Location::all();
             
       
        return view('assets.create', compact('users', 'vendors', 'assetCategories','locations',));
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Create the asset
            $asset = Asset::create($validated);

            $currentUser = $request->user_id == 'other' ? $request->manual_current_user : $request->user_id;
            $previousUser = $request->previous_user_id == 'other' ? $request->manual_previous_user : $request->previous_user_id;

            // Log the asset creation in asset history
            AssetHistory::create([
                'asset_id' => $asset->id,
                'user_id' => auth()->id(),
                'action' => 'created',
                'description' => 'Asset was created.',
            ]);

            // Log the created asset for debugging
            \Log::info('Asset created: ', $asset->toArray());

            // Check if asset creation was successful
            if (!$asset) {
                return back()->withErrors(['asset' => 'Failed to create asset.']);
            }

            // Log changes in the audit trail for every column
            foreach ($validated as $column => $newValue) {
                AuditTrail::create([
                    'user_id'    => auth()->id(),
                    'table_name' => 'assets',
                    'column_name'=> $column,
                    'old_value'  => null, // No old value since it's a new record
                    'new_value'  => $newValue,
                    'action'     => 'create',
                ]);
            }

            // Notify admins about the new asset
            $admins = User::whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->get();

            Notification::send($admins, new AssetCreated($asset));

            // Redirect or return response
            return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Log the error message
            \Log::error('Database error while creating asset: ' . $e->getMessage());

            // Check for duplicate entry error (MySQL error code 1062)
            if ($e->errorInfo[1] == 1062) {
                return back()->withErrors(['error' => 'Duplicate entry detected: This asset already exists.']);
            }

            // Return a generic error message to the front-end
            return back()->withErrors(['error' => 'An error occurred while creating the asset. Please try again later.']);

        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error while creating asset: ' . $e->getMessage());

            // Return a generic error message to the front-end
            return back()->withErrors(['error' => 'An error occurred while creating the asset.']);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        
        $this->authorize('view', $asset); 

        // Load related data
        $asset->load('assetCategory', 'vendor', 'department', 'location', 'user');

        // Get all required data for form select options
        $assetCategories = AssetCategory::all();
        $vendors = Vendor::all();
        
        $users = User::all();
        // $invoices = Invoice::all();
        // $insurances = Insurance::all();
        $vendors = Vendor::all();

       

        return view('assets.show', compact('asset', 'assetCategories', 'users', 'vendors'));
    }

    public function itControlForm($id)
    {
        $asset = Asset::findOrFail($id);
        return view('assets.pdf', compact('asset'));
    }

    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        
        $this->authorize('edit', $asset);

        $assets = $asset;


        $assetCategories = AssetCategory::all();
        $vendors = Vendor::all();
        $locations = Location::all();
        
        $users = User::all();
        // $invoices = Invoice::all();
        // $insurances = Insurance::all();
        
        return view('assets.edit', compact('asset', 'users', 'vendors', 'assetCategories','locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {

        $oldUser = $asset->user_id;  // Assuming you track the current owner
        $newUser = $request->user_id;

        // Authorize the action
        $this->authorize('edit', $asset);
    
        // Fetch the old values for history logging
        $oldAsset = $asset->getOriginal(); 
    
        // Validate the request
        $validated = $request->validate([
            'serial_number' => [
                'required',
                Rule::unique('assets')->ignore($asset->id),
            ],
            'asset_number' => [
                'required',
                Rule::unique('assets')->ignore($asset->id),
            ],
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'category' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'previous_user_id' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:asset_categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            // 'insurance_id' => 'nullable|exists:insurances,id',
            'status' => 'nullable|string|max:255',
        ]);
    
        // // Check if user_id has changed
        // if ($asset->user_id !== $validated['user_id']) {
        //     // Set the previous_user_id to the current user_id before reassigning
        //     $validated['previous_user_id'] = $asset->user_id;
        // }

        // Check if vendor is being updated
        if ($request->has('vendor_id')) {
            // Vendor is being assigned, so user should be set to null and marked unassigned
            $validated['previous_user_id'] = $asset->user_id; // Set the previous user
            $validated['user_id'] = null;                     // Unassign the user
            $validated['vendor_id'] = $request->vendor_id;    // Assign to vendor
        } elseif ($request->has('user_id')) {
            // If reassigned to a user, nullify the vendor and set the user
            $validated['vendor_id'] = null;
            $validated['previous_user_id'] = $asset->user_id; // Keep track of previous user
            $validated['user_id'] = $request->user_id;        // Assign to new user
        } else {
            // Handle case where neither user nor vendor are assigned (mark as unassigned)
            $validated['user_id'] = null;
            $validated['vendor_id'] = null;
            $validated['previous_user_id'] = $asset->user_id; // Store the last user
        }

        // Check if the asset is assigned to a vendor
        if ($request->user_or_vendor === 'vendor') {
            $asset->user_id = null; // Set user_id to null
            $asset->vendor_id = $request->vendor; // Assuming you have a vendor_id field in your asset table
        } else {
            $asset->vendor_id = null; // Clear vendor_id if assigned to a user
            $asset->user_id = $request->user_id; // Assign to user
        }
        
       
        // Update the asset with the validated data
        $asset->update($validated);

        // Track the history
        AssetHistory::create([
            'asset_id' => $asset->id,
            'previous_user_id' => $oldUser,
            'current_user_id' => $newUser,
            'asset_description' => $asset->description,
            'status' => 'updated',
            'changes' => json_encode($asset->getChanges()),
        ]);
    
        // Notify the user who updated the asset
        auth()->user()->notify(new AssetUpdated($asset));
    
        // Fetch admin users by role
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');  // Assuming 'admin' is the role name
        })->get();
    
        // Send notification to the admin users
        Notification::send($admins, new AssetUpdated($asset));
    
        return redirect()->route('assets.index')->with('success', 'Asset updated successfully');
    }
    

    protected function describeChanges($oldAsset, $changes)
    {
        $description = '';
        foreach ($changes as $key => $value) {
            $description .= "$key changed from '{$oldAsset[$key]}' to '{$value}'. ";
        }
        return $description;
    }

    public function downloadForm($assetId)
    {
        $asset = Asset::find($assetId); // Fetch the asset

        // Load the view and pass the asset data to it
        $pdf = Pdf::loadView('assets.pdf', compact('asset'));

        // Download the PDF file
        return $pdf->download('IT_Control_Form.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        // Soft delete the asset (archive)
        $asset->delete();
        
        // Soft delete the related records in asset_history
        $asset->assetHistory()->delete();

        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();
        
        // Send notification to users about the asset archiving
        Notification::send($admins, new AssetDeleted($asset));
        
        // Redirect or return response
        return redirect()->route('assets.index')->with('success', 'Asset archived successfully.');
    }

    
    
    
    public function getTotalAssetCount()
    {
        $totalAssets = Asset::count(); 
        return response()->json(['totalAssets' => $totalAssets]);
    }

    

    public function exportToPdf($id)
    {
        // Fetch the asset data using the id
        $asset = Asset::findOrFail($id);

        // Load the view with the asset data
        $pdf = PDF::loadView('assets.pdf', compact('asset'));

        // Stream or download the PDF
        return $pdf->download('IT_Control_Form_' . $asset->id . '.pdf');
    }

    public function restore($id)
    {
        $asset = Asset::withTrashed()->findOrFail($id);

        if ($asset->trashed()) {
            $asset->restore();
            return redirect()->route('assets.index')->with('success', 'Asset restored successfully.');
        }

        return redirect()->route('assets.index')->with('error', 'Asset cannot be restored.');
    }
    
    
}
