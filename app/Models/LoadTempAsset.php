<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadTempAsset extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected $fillable = [
        'make', 'model', 'serial_number', 'asset_number', 'category_id', 'facility', 
        'user_id', 'previous_user_id', 'date', 'vendor', 'location_id', 
        'vendor_id','facility_id', 'status',
    ];
    

    // public function assetCategory()
    // {
    //     return $this->belongsTo(AssetCategory::class, 'category_id', 'id');
    // }

    // public function locations()
    // {
    //     return $this->belongsTo(Location::class, 'location_id', 'id');
    // }
    // public function vendor()
    // {
    //     return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    // }

    // // Relationship with the current user
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    // // Relationship with the previous user
    // public function previousUser()
    // {
    //     return $this->belongsTo(User::class, 'previous_user_id');
    // }
}
