<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'belgeler' => 'array',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['last_name'] . ' ' . $attributes['first_name'] . ' ' . $attributes['father_name'],
        );
    }
} 