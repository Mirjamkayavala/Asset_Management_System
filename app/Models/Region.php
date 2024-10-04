<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable =[
        'region_name',
        'zipcode',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'region_id');
    }
}
