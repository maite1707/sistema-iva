<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iva_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('movement_type', ['ANTICIPO', 'RETENCION', 'PERCEPCION']);
            $table->enum('direction', ['RECIBIDO', 'EMITIDO']);
            $table->decimal('percentage', 5, 2);
            $table->string('counterparty_nit', 30)->nullable();
            $table->string('counterparty_dui', 20)->nullable();
            $table->string('counterparty_name', 200)->nullable();
            $table->string('document_type', 50)->nullable();
            $table->string('document_resolution', 50)->nullable();
            $table->string('document_series', 50)->nullable();
            $table->string('document_number', 50)->nullable();
            $table->date('issue_date');
            $table->decimal('taxable_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'issue_date'], 'iva_mov_company_date_idx');
            $table->index(['company_id', 'movement_type', 'direction'], 'iva_mov_type_dir_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iva_movements');
    }
};