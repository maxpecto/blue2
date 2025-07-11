<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'kullanilan_parcalar' => 'array',
    ];

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function maintenanceRule()
    {
        return $this->belongsTo(MaintenanceRule::class);
    }
} 