<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('document_type_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('document_number', 50);
            $table->date('issue_date');
            $table->enum('taxpayer_type_snapshot', ['CONTRIBUYENTE', 'CONSUMIDOR_FINAL']);
            $table->decimal('taxable_amount', 12, 2)->default(0);
            $table->decimal('exempt_amount', 12, 2)->default(0);
            $table->decimal('iva_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'issue_date']);
            $table->index(['company_id', 'taxpayer_type_snapshot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};