<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IvaMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'movement_type',
        'direction',
        'percentage',
        'counterparty_nit',
        'counterparty_dui',
        'counterparty_name',
        'document_type',
        'document_resolution',
        'document_series',
        'document_number',
        'issue_date',
        'taxable_amount',
        'tax_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'percentage' => 'decimal:2',
            'taxable_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function annexCode(): string
    {
        return match (true) {
            $this->movement_type === 'ANTICIPO' && $this->direction === 'RECIBIDO' => 'A6',
            $this->movement_type === 'RETENCION' && $this->direction === 'RECIBIDO' => 'A7',
            $this->movement_type === 'PERCEPCION' && $this->direction === 'RECIBIDO' => 'A8',
            $this->movement_type === 'PERCEPCION' && $this->direction === 'EMITIDO' => 'A9',
            $this->movement_type === 'RETENCION' && $this->direction === 'EMITIDO' => 'A10',
            $this->movement_type === 'ANTICIPO' && $this->direction === 'EMITIDO' => 'A11',
            $this->movement_type === 'RETENCION' && $this->direction === 'EMITIDO' && (float) $this->percentage === 13.0 => 'A12',
            default => throw new \LogicException('Combinación de movimiento IVA no reconocida.'),
        };
    }
}