<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('commercial_name', 200)->nullable();
            $table->enum('document_type', ['NIT', 'DUI', 'NRC', 'PASAPORTE', 'CARNET_RESIDENTE', 'OTRO'])
                ->default('DUI');
            $table->string('document_number', 30)->nullable();
            $table->string('nrc', 30)->nullable();
            $table->enum('taxpayer_type', ['CONTRIBUYENTE', 'CONSUMIDOR_FINAL'])
                ->default('CONSUMIDOR_FINAL');
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'document_number']);
            $table->index(['company_id', 'taxpayer_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};