<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'document_type_id',
        'document_number',
        'issue_date',
        'taxable_amount',
        'exempt_amount',
        'iva_amount',
        'total',
        'notes',
        'status',
        'is_excluded_subject',
        'retention_amount',
        'renta_operation_type',
        'renta_classification',
        'renta_sector',
        'renta_cost_type',
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}