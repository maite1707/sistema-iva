<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxAnnexGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'tax_annex_id',
        'period_month',
        'period_year',
        'total_records',
        'file_path',
        'generated_by',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function taxAnnex(): BelongsTo
    {
        return $this->belongsTo(TaxAnnex::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}