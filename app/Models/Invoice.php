<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'amount',
        'file_path',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'invoice_id');
    }
}
