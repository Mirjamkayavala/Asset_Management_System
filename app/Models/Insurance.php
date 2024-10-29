<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurances';

    protected $fillable = [
        'asset_id',
        'claim_number',
        'insurance_type',
        'amount',
        'status',
        'user_id',
        'approval_date',
        'rejection_date',
        'claim_date',
        'description',
        'insurance_document',
        'serial_number',
    ];

    // Relationship to Asset by asset_id
    public function assetById()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    // Relationship to Asset by serial_number
    public function assetBySerial()
    {
        return $this->belongsTo(Asset::class, 'serial_number', 'serial_number');
    }

    // Relationship to Asset using the default asset_id
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
