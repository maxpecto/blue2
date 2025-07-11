<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MileageLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class, 'motor_id');
    }
} 