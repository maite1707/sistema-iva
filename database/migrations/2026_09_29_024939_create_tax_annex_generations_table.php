<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_annex_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('tax_annex_id')
                ->constrained('tax_annexes')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->unsignedInteger('total_records')->default(0);
            $table->string('file_path', 500)->nullable();
            $table->foreignId('generated_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->index(['company_id', 'tax_annex_id', 'period_year', 'period_month'], 'tax_annex_gen_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_annex_generations');
    }
}; 