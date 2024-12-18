<?php

namespace App\Http\Controllers;

use Excel;
use App\Models\User;
use App\Models\Asset;
use App\Models\Vendor;
use App\Models\Department;
// use App\Models\Insurance;
use App\Models\Location;
use App\Models\Facility;
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
use App\Notifications\AssetDeletionApprovalRequested;

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
            ->paginate(10);

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
        $facilities = Facility::all();
       
        // $insurances = Insurance::all();

        $In_UseCount = Asset::where('status', 'In Use')->count();
        $In_StorageCount = Asset::where('status', 'In Storage')->count();
        $BrokenCount = Asset::where('status', 'Broken')->count();
        $WrittenOffCount = Asset::where('status', 'Written Off')->count();
        $NewCount = Asset::where('status', 'New')->count();
        $OldCount = Asset::where('status', 'Old')->count();

        return view('assets.index', compact('paginatedAssets', 'groupedAssets', 'users', 'In_UseCount', 'In_StorageCount', 'BrokenCount', 'WrittenOffCount', 'vendors', 'assetCategories', 'departments', 'NewCount', 'OldCount', 'data', 'locations','facilities'));
    }

    public function import(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            ]);
    
            $file = $request->file('file')->store('temp');
            $filePath = storage_path('app/' . $file);
    
            // Import the file to load_temp_assets table
            Excel::import(new AssetTempImport, $filePath);
    
            // Retrieve data from load_temp_assets table
            $tempAssets = DB::table('load_temp_assets')->get();
    
            foreach ($tempAssets as $tempAsset) {
                // Check if 'make' and 'model' are present
                if (empty($tempAsset->make) || empty($tempAsset->model)) {
                    Log::warning('Skipped row due to missing make or model: Make: ' . ($tempAsset->make ?? 'NULL') . ', Model: ' . ($tempAsset->model ?? 'NULL'));
                    continue; // Skip this row
                }
            
                // Check if both serial_number and asset_number are null
                if (is_null($tempAsset->serial_number) && is_null($tempAsset->asset_number)) {
                    $exists = false; // Proceed to insert since there are no identifiers to check for duplicates
                } else {
                    // Check for existing record where either serial_number or asset_number matches
                    $exists = Asset::query()
                        ->where(function ($query) use ($tempAsset) {
                            if (!is_null($tempAsset->serial_number)) {
                                $query->where('serial_number', $tempAsset->serial_number);
                            }
                            if (!is_null($tempAsset->asset_number)) {
                                $query->orWhere('asset_number', $tempAsset->asset_number);
                            }
                        })
                        ->exists();
                }
            
                if (!$exists) {
                    // Convert Excel serial date if present
                    $convertedDate = null;
                    if (!empty($tempAsset->date)) {
                        $convertedDate = Carbon::createFromFormat('Y-m-d', gmdate("Y-m-d", ($tempAsset->date - 25569) * 86400))->toDateString();
                    }
            
                    // Retrieve user IDs for current and previous users
                    $user_id = User::where('name', $tempAsset->current_user ?? null)->value('id');
                    $previous_user_id = User::where('name', $tempAsset->previous_user ?? null)->value('id');
            
                    // Insert into assets table
                    Asset::create([
                        'make'             => $tempAsset->make,
                        'model'            => $tempAsset->model,
                        'category_id'      => $tempAsset->category_id,
                        'serial_number'    => $tempAsset->serial_number,
                        'asset_number'     => $tempAsset->asset_number,
                        'facility'     => $tempAsset->facility,
                        'date'             => $convertedDate,
                        'vendor'           => $tempAsset->vendor,
                        'previous_user_id' => $tempAsset->previous_user_id,
                        'user_id'          => $tempAsset->user_id,
                        'location_id'      => $tempAsset->location_id,
                        'vendor_id'      => $tempAsset->vendor_id,
                        'facility_id'      => $tempAsset->facility_id,
                        'status'           => $tempAsset->status,
                    ]);
                } else {
                    // Log duplicate and continue
                    Log::warning('Duplicate entry detected: Serial Number: ' . ($tempAsset->serial_number ?? 'NULL') . ' or Asset Number: ' . ($tempAsset->asset_number ?? 'NULL'));
                }
            }
            
            // Optionally clear the load_temp_assets table after import
            DB::table('load_temp_assets')->truncate();
    
            return redirect()->route('assets.index')->with('success', 'File successfully imported');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
    
            Log::error('Import validation errors: ' . implode('; ', $messages));
    
            return redirect()->route('assets.index')->with('error', 'Invalid file, please check the file again: ' . implode('; ', $messages));
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
    
            return redirect()->route('assets.index')->with('error', 'Import error: ' . $e->getMessage());
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
        $facilities = Facility::all();
             
       
        return view('assets.create', compact('users', 'vendors', 'assetCategories','locations','facilities'));
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

            if (in_array($request->status, ['Broken', 'WrittenOff'])) {
                // Redirect to the insurance create form with asset_id and serial_number
                return redirect()->route('insurance.create', [
                    'asset_id' => $asset->id,
                    'serial_number' => $asset->serial_number,
                ])->with('success', 'Asset created successfully and redirected to insurance form.');
            }

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
            // $admins = User::whereHas('role', function($query) {
            //     $query->where('name', 'admin');
            // })->get();

            // Notification::send($admins, new AssetCreated($asset));

            $admins = User::whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->get();
            
            $technicians = User::whereHas('role', function($query) {
                $query->where('name', 'technician');
            })->get();
            
            $recipients = $admins->merge($technicians); // Merge admins and technicians collections
            
            Notification::send($recipients, new AssetCreated($asset));

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
        $asset->load('assetCategory', 'vendor', 'department', 'locations', 'user', 'facility');
        // dd($asset->locations);
        // Get all required data for form select options
        $assetCategories = AssetCategory::all();
        $vendors = Vendor::all();
        
        $users = User::all();
        $locations = Location::all();
        // $invoices = Invoice::all();
        // $insurances = Insurance::all();
        $vendors = Vendor::all();
        $facilities = Facility::all();

       

        return view('assets.show', compact('asset', 'assetCategories', 'users', 'vendors', 'locations', 'facilities'));
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
        $facilities = Facility::all();
        
        $users = User::all();
        // $invoices = Invoice::all();
        // $insurances = Insurance::all();
        
        return view('assets.edit', compact('asset', 'users', 'vendors', 'assetCategories','locations', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
{
    $oldUser = $asset->user_id;  
    $newUser = $request->user_id;

    // Authorize the action
    $this->authorize('edit', $asset);

    // Fetch the old values for history logging
    $oldAsset = $asset->getOriginal(); 

    // Validate the request
    $validated = $request->validate([
        'serial_number' => [
            'nullable',
            Rule::unique('assets')->ignore($asset->id),
        ],
        'asset_number' => [
            'nullable',
            Rule::unique('assets')->ignore($asset->id),
        ],
        'make' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'user_id' => 'nullable|exists:users,id',
        'category' => 'nullable|string|max:255',
        'vendor' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'date' => 'nullable|date',
        'price' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
        'previous_user_id' => 'nullable|exists:users,id',
        'category_id' => 'nullable|exists:asset_categories,id',
        'location_id' => 'nullable|exists:locations,id',
        'vendor_id' => 'nullable|exists:vendors,id',
        'facility_id' => 'nullable|exists:facilities,id',
        'facility' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:255',
    ]);

    // Assign user, vendor, or facility
    if ($request->has('vendor_id')) {
        $validated['previous_user_id'] = $asset->user_id;
        $validated['user_id'] = null;
        $validated['vendor_id'] = $request->vendor_id;
    } elseif ($request->has('user_id')) {
        $validated['vendor_id'] = null;
        $validated['previous_user_id'] = $asset->user_id;
        $validated['user_id'] = $request->user_id;
    } elseif ($request->has('facility')) {
        $validated['vendor_id'] = null;
        $validated['user_id'] = null;
        $validated['previous_user_id'] = $asset->user_id;
        $validated['facility'] = $request->facility;
    } else {
        $validated['user_id'] = null;
        $validated['vendor_id'] = null;
        $validated['facility'] = null;
        $validated['previous_user_id'] = $asset->user_id;
    }

    // Handle reassignment logic
    if ($request->user_or_vendor === 'vendor') {
        $asset->user_id = null;
        $asset->facility_id = null;
        $asset->vendor_id = $request->vendor;
    } elseif ($request->user_or_vendor === 'user') {
        $asset->vendor_id = null;
        $asset->facility_id = null;
        $asset->user_id = $request->user_id;
    } elseif ($request->user_or_vendor === 'facility') {
        $asset->vendor_id = null;
        $asset->user_id = null;
        $asset->facility_id = $request->facility;
    } else {
        $asset->vendor_id = null;
        $asset->user_id = null;
        $asset->facility = null;
    }

    // Check if the asset is already in the insurance table
    $existingInsurance = \App\Models\Insurance::where('asset_id', $asset->id)->first();

    if ($existingInsurance) {
        // If the asset already exists in the insurance table, issue a warning and redirect back
        return redirect()->back()->with('warning', 'This asset is already listed in the insurance table.');
    }

    if (in_array($request->status, ['Broken', 'WrittenOff']) && 
        !in_array($asset->status, ['Broken', 'WrittenOff'])) {
        
        // Create a new insurance record and populate the required fields
        \App\Models\Insurance::create([
            'asset_id' => $asset->id,
            'serial_number' => $asset->serial_number,
        ]);
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

    // Notify admin users
    $admins = User::whereHas('roles', function($query) {
        $query->where('role_name', 'admin'); 
    })->get();
    
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

        // Notify admins (optional, based on your requirements)
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();
        Notification::send($admins, new AssetDeleted($asset));

        // Notify the costing department for approval
        $costingDepartmentUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'costing_department');  // Assuming 'costing_department' is the role name
        })->get();
        Notification::send($costingDepartmentUsers, new AssetDeletionApprovalRequested($asset));

        // Redirect or return response
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully. Approval for deletion has been requested.');
    }

    public function forceDelete($id)
{
    // Find the asset, including soft-deleted ones
    $asset = Asset::withTrashed()->findOrFail($id);

    // Check if the user is authorized to force delete the asset
    $this->authorize('forceDelete', $asset);

    // Permanently delete the asset
    $asset->forceDelete();

    return redirect()->route('assets.index')->with('success', 'Asset permanently deleted.');
}


    public function deletedAssets()
    {
        // Retrieve all soft-deleted assets
        $deletedAssets = Asset::onlyTrashed()->get();

        return view('assets.deleted', compact('deletedAssets'));
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
        $asset = Asset::withTrashed()->findOrFail($id); // Retrieve the asset first
        $this->authorize('restore', $asset); // Now authorize

        if ($asset->trashed()) {
            $asset->restore();
            return redirect()->route('assets.deleted')->with('success', 'Asset restored successfully.');
        }

        return redirect()->route('assets.deleted')->with('error', 'Asset cannot be restored.');
    }

    public function clearArchived()
{
    // Ensure the user has the appropriate permission
    // $this->authorize('clear-archived-assets');

    // Permanently delete all archived assets
    Asset::onlyTrashed()->forceDelete();

    // Redirect back with a success message
    return redirect()->route('assets.deleted')->with('success', 'All archived assets have been permanently deleted.');
}

    public function getAuditTrail($id)
    {
        $auditTrails = AuditTrail::where('asset_id', $id)->get();
        
        // Example data structure
        $data = $auditTrails->map(function ($trail) {
            return [
                'current_user' => $trail->current_user,
                'previous_user' => $trail->previous_user,
                'updated_data' => $trail->updated_data,
                'duration' => $trail->start_date . ' to ' . $trail->end_date,
            ];
        });

        return response()->json($data);
    }
    
    
    
}
