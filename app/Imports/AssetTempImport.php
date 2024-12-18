<?php

namespace App\Imports;

use App\Models\LoadTempAsset;
use App\Models\User;
use App\Models\Location;
use App\Models\Vendor;
use App\Models\AssetCategory;
use App\Models\Facility;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class AssetTempImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Define the expected columns
        $expectedColumns = [
            'make', 'model', 'serial_number', 'asset_number'
        ];
        
        // Check if all expected columns are present in the $row array
        foreach ($expectedColumns as $column) {
            if (!array_key_exists($column, $row)) {
                throw new \Exception("Invalid file, please check the file again. Missing column: {$column}");
            }
        }
        

        // Log the row for debugging
        Log::info($row);

        // Retrieve foreign key IDs 
        $user_id = User::where('name', trim($row['current_user']) ?? null)->value('id');
        $previous_user_id = User::where('name', trim($row['previous_user']) ?? null)->value('id');
        $location_id = Location::where('location_name', trim($row['location']) ?? null)->value('id');
        $facility_id = Facility::where('facility_name', trim($row['facility']) ?? null)->value('id');
        
        $category_id = AssetCategory::where('category_name', trim($row['category']) ?? null)->value('id');
        // 
        $vendor_id = Vendor::where('vendor_name', $row['vendor'] ?? null)->value('id');
        // Log::info($user_id);

        // Return the model instance if all columns match
        return new LoadTempAsset([
            'make'          => $row['make'],
            'model'         => $row['model'],
            'serial_number' => $row['serial_number'],
            'asset_number'  => $row['asset_number'],
            'category_id'      => $category_id,                       
            'facility'      => $row['facility'] ?? null,           
            // 'user_id'  => $row['current_user'] ?? null,                           
            'user_id'  => $user_id,                           
            'date'          => $row['date'] ?? null,
            // 'user_id' => $previous_user_id,                  
            'vendor'        => $row['vendor'] ?? null,
            'location_id'      => $location_id,                       
            'facility_id'      => $facility_id,                       
            'vendor_id'      => $vendor_id,                       
            'status'        => $row['status'] ?? null,
        ]);
    }
}
