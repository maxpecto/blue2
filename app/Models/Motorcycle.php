<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorcycle extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'gorseller' => 'array',
        'belgeler' => 'array',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function mileageLogs()
    {
        return $this->hasMany(MileageLog::class, 'motor_id');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function maintenanceTrackings()
    {
        return $this->hasMany(MotorcycleMaintenanceTracking::class);
    }
} 