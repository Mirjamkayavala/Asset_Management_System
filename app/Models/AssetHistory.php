<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;
    protected $table = 'asset_history';
    protected $fillable = ['user_id', 'asset_id', 'assigned_to', 'assigned_by', 'assignment_date', 'return_date', 'action'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function currentUser()
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }


    // Relationship for the assigned_to user
    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relationship for the assigned_by user
    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}

