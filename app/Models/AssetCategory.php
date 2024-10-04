<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_name',
        'category_code',
       
    ];

        // Relationship with User (created_by)
        public function creator()
        {
            return $this->belongsTo(User::class, 'created_by');
        }
    
        public function assets(): BelongsToMany
        {
            return $this->belongsToMany(Asset::class, 'asset_asset_category')
                        ->withPivot('created_by')
                        ->withTimestamps();
        }
}
