<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssignmentHistory extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'user_id', 'changed_by', 'change_type', 'changes'];

    protected $casts = [
        'changes' => 'array',
    ];

    protected $table = 'asset_assignment_history';

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
