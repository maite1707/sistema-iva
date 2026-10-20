<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('third_party_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // Datos del mandante (la persona/empresa a quien le vendemos en su nombre)
            $table->string('mandante_nit_nrc', 30)->nullable();
            $table->string('mandante_name', 200);
            $table->string('mandante_dui', 20)->nullable();

            // Documento emitido al cliente final (CCF o Factura)
            $table->string('document_type', 50)->nullable();
            $table->string('document_series', 50)->nullable();
            $table->string('document_resolution', 50)->nullable();
            $table->string('document_number', 50)->nullable();
            $table->date('document_date');
            $table->decimal('sale_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2);

            // Comprobante de Liquidación emitido después al mandante
            $table->string('settlement_series', 50)->nullable();
            $table->string('settlement_resolution', 50)->nullable();
            $table->string('settlement_number', 50)->nullable();
            $table->date('settlement_date')->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'document_date'], 'third_party_sales_company_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('third_party_sales');
    }
};