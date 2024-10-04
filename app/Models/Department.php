<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'department_name',
        'department_code',
        // 'user_id',

    ];

    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assetAssignments()
    {
        return $this->hasMany(App\Models\AssetAssignment::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
