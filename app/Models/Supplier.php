<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'commercial_name',
        'document_type',
        'document_number',
        'nrc',
        'is_iva_withholding_agent',
        'email',
        'phone',
        'address',
        'department',
        'municipality',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_iva_withholding_agent' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}