<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'document_type_id',
        'document_number',
        'issue_date',
        'taxpayer_type_snapshot',
        'taxable_amount',
        'exempt_amount',
        'iva_amount',
        'total',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'taxable_amount' => 'decimal:2',
            'exempt_amount' => 'decimal:2',
            'iva_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}