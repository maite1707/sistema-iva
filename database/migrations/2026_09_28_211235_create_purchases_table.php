<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('document_type_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('document_number', 50);
            $table->date('issue_date');
            $table->decimal('taxable_amount', 12, 2)->default(0);
            $table->decimal('exempt_amount', 12, 2)->default(0);
            $table->decimal('iva_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'issue_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};