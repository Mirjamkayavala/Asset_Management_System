<?php

namespace App\Imports;

use App\Models\LoadTempAsset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class AssetTempImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        Log::info($row);
        
        return new LoadTempAsset([
            // Make	Model	Serial Number	Asset Number	Category	Current User	Date	Previous User	Vendor	Status

            'make'=>$row['make'],
            'model'=>$row['model'],
            'serial_number'=>$row['serial_number'],
            'asset_number'=>$row['asset_number'],
            'category'=>$row['category'],
            // 'current_user'=>$row['current_user'],
            'current_user'           => isset($row['current_user']) ? $row['current_user'] : null,
            'date'           => isset($row['date']) ? $row['date'] : null,
            'previous_user'  => isset($row['previous_user']) ? $row['previous_user'] : null,
            'vendor'         => isset($row['vendor']) ? $row['vendor'] : null,
            'status'         => isset($row['status']) ? $row['status'] : null,
        ]);
    }
}
