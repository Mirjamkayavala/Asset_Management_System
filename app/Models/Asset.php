<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;



class Asset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['date'];

    protected $fillable = [
        'make', 'model', 'serial_number', 'user_id', 'asset_number','category' ,'date','vendor' ,'location', 'previous_user_id', 'category_id','location_id', 'vendor_id', 'status'
    ];

    // Define relationships
    public function assetCategories()
    {
        return $this->belongsTo(AssetCategory::class,'category_id','id');
    }

    public function locations()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function vendors()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

   // Define the relationship with the User model (current user)
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship with the User model (previous user)
    public function previousUser()
    {
        return $this->belongsTo(User::class, 'previous_user_id');
    }

    public function asset_history()
    {
        return $this->hasMany(AssetHistory::class, 'asset_id');
    }

    public function departments()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function insurances()
    {
        return $this->belongsTo(Insurance::class, 'insurance_id', 'id');
    }

    // public function claim()
    // {
    //     return $this->belongsTo(Insurance::class, 'claim_number');
    // }

    // public function insuranceStatus()
    // {
    //     return $this->belongsTo(Insurance::class, 'insurance_status');
    // }

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
   
    public static function boot()
    {
        parent::boot();

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

        // static::creating(function ($model) {
        //     \App\Models\AuditTrail::create([
        //         'user_id' => auth()->id(),
        //         'table_name' => $model->getTable(),
        //         'column_name' => null, // No specific column for create
        //         'old_value' => null,
        //         'new_value' => json_encode($model->getAttributes()), // Log entire row
        //         'action' => 'create',
        //     ]);
        // });

        static::deleting(function ($model) {
            \App\Models\AuditTrail::create([
                'user_id' => auth()->id(),
                'table_name' => $model->getTable(),
                'column_name' => null, // No specific column for delete
                'old_value' => json_encode($model->getAttributes()), // Log entire row
                'new_value' => null,
                'action' => 'delete',
            ]);
        });
    }

    
}
