<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('commercial_name', 200)->nullable();
            $table->enum('document_type', ['NIT', 'DUI', 'NRC', 'PASAPORTE', 'CARNET_RESIDENTE', 'OTRO'])
                ->default('NIT');
            $table->string('document_number', 30)->nullable();
            $table->string('nrc', 30)->nullable();
            $table->boolean('is_iva_withholding_agent')->default(false);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'document_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};