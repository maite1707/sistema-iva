<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IvaRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'percentage',
        'is_default',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ];
    }
}