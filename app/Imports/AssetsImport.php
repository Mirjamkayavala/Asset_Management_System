<?php
namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Vendor;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class AssetsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Fetch the user, previous user, category, and vendor using their names or other identifiers
        $user = User::where('name', $row['user_name'] ?? null)->first();
        $previousUser = User::where('name', $row['previous_user_name'] ?? null)->first();
        $category = AssetCategory::where('category_name', $row['category_name'] ?? null)->first();
        $vendor = Vendor::where('vendor_name', $row['vendor_name'] ?? null)->first();

        // Check if 'serial_number' already exists to avoid duplicates
        $existingAsset = Asset::where('serial_number', $row['serial_number'] ?? null)->first();
        if (!$user || !$previousUser || !$category || !$vendor) {
            // Print the row data to identify missing data
            // dd('Error: Missing or invalid data in row:', $row);
            return null;
        }

        dd('hello');
        // Insert the asset data into the assets table
        return new Asset([
            'make'             => $row['make'] ?? null,
            'model'            => $row['model'] ?? null,
            'serial_number'    => $row['serial_number'] ?? null,
            'user_id'          => $user ? $user->id : null,
            'asset_number'     => $row['asset_number'] ?? null,
            'date'             => isset($row['date']) ? \Carbon\Carbon::parse($row['date']) : \Carbon\Carbon::now(),
            'previous_user_id' => $previousUser ? $previousUser->id : null,
            'category_id'      => $category ? $category->id : null,
            'vendor_id'        => $vendor ? $vendor->id : null,
            'status'           => $row['status'] ?? null,
        ]);
    }
}
