<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Insurance;
use App\Models\Department;
use App\Models\Location;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\AssetCategory;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search logic for different models
        $assets = Asset::where('asset_number', 'like', "%$query%")
                ->orWhere('serial_number', 'like', "%$query%")
                ->orWhere('make', 'like', "%$query%")
                ->orWhere('model', 'like', "%$query%")
                ->get();

        $departments = Department::where('department_name', 'like', "%{$query}%")
                                 ->orWhere('department_code', 'like', "%{$query}%")
                                 ->get();

        $locations = Location::where('location_name', 'like', "%{$query}%")
                             ->orWhere('region_id', 'like', "%{$query}%")
                             ->get();

        $regions = Region::where('region_name', 'like', "%{$query}%")
                             ->orWhere('zipcode', 'like', "%{$query}%")
                             ->get();

        $vendors = Vendor::where('vendor_name', 'like', "%{$query}%")
                         ->get();

        $assetCategories = AssetCategory::where('category_name', 'like', "%{$query}%")
                                        ->orWhere('category_code', 'like', "%{$query}%")
                                        ->get();

        $users = User::where('name', 'like', "%{$query}%")
                                        ->orWhere('contact_number', 'like', "%{$query}%")
                                        ->get();
        $invoices = Invoice::where('invoice_number', 'like', "%{$query}%")
                                        
                                        ->get();
        $insurances = Insurance::where('claim_number', 'like', "%{$query}%")
                         ->get();

        return view('search.results', compact('assets', 'departments', 'locations', 'vendors', 'assetCategories','regions', 'invoices', 'users', 'insurances'));
    }
}
