<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxAnnex extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'version',
        'tax_form',
        'f07_box',
        'valid_from',
        'valid_until',
        'active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_until' => 'date',
            'active' => 'boolean',
        ];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(TaxAnnexField::class);
    }
}