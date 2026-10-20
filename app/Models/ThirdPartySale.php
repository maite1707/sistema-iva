<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThirdPartySale extends Model
{
    protected $fillable = [
        'company_id',
        'mandante_nit_nrc',
        'mandante_name',
        'mandante_dui',
        'document_type',
        'document_series',
        'document_resolution',
        'document_number',
        'document_date',
        'sale_amount',
        'tax_amount',
        'settlement_series',
        'settlement_resolution',
        'settlement_number',
        'settlement_date',
        'status',
    ];

    protected $casts = [
        'document_date' => 'date',
        'settlement_date' => 'date',
        'sale_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}