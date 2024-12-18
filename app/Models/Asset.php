<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['date'];

    protected $fillable = [
        'make', 
        'model', 
        'serial_number', 
        'user_id', 
        'asset_number',
        'category',
        'date',
        'price',
        'vendor',
        'location',
        'facility',
        'previous_user_id', 
        'category_id',
        'location_id',
        'vendor_id',
        'facility_id',
        'storage',
        'status'
    ];

    // Define relationships
    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id', 'id');
    }

    public function locations()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    
    // public function location()
    // {
    //     return $this->belongsTo(Location::class);
    // }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'id');
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    // Relationship with the current user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with the previous user
    public function previousUser()
    {
        return $this->belongsTo(User::class, 'previous_user_id');
    }

    public function assetHistory()
    {
        return $this->hasMany(AssetHistory::class, 'asset_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'asset_id');
    }


    // Relationship with insurances by asset's serial number
    public function insurancesBySerialNumber()
    {
        return $this->hasMany(Insurance::class, 'serial_number', 'serial_number');
    }

    // Relationship with invoices
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Boot method to handle audit logging
    public static function boot()
    {
        parent::boot();

        // Log updates
        static::updating(function ($model) {
            foreach ($model->getDirty() as $columnName => $newValue) {
                $oldValue = $model->getOriginal($columnName);

                \App\Models\AuditTrail::create([
                    'user_id' => auth()->id(),
                    'table_name' => $model->getTable(),
                    'column_name' => $columnName,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'action' => 'update',
                ]);
            }
        });

        // Log deletions
        static::deleting(function ($model) {
            \App\Models\AuditTrail::create([
                'user_id' => auth()->id(),
                'table_name' => $model->getTable(),
                'column_name' => null,
                'old_value' => json_encode($model->getAttributes()),
                'new_value' => null,
                'action' => 'delete',
            ]);
        });
    }
}
