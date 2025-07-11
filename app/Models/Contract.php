<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'teslimat_gorselleri' => 'array',
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
} 