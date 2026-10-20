<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_annex_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_annex_id')
                ->constrained('tax_annexes')
                ->cascadeOnDelete();
            $table->unsignedInteger('field_order');
            $table->string('field_code', 100);
            $table->string('field_name', 255);
            $table->enum('data_type', ['TEXT', 'INTEGER', 'DECIMAL', 'DATE', 'NIT', 'NRC', 'DUI'])
                ->default('TEXT');
            $table->unsignedInteger('max_length')->nullable();
            $table->boolean('required')->default(false);
            $table->string('source', 255)->nullable();
            $table->string('transform_rule', 255)->nullable();
            $table->text('validation_rule')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['tax_annex_id', 'field_code']);
            $table->index(['tax_annex_id', 'field_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_annex_fields');
    }
};