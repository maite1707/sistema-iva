<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('commercial_name', 200)->nullable();
            $table->string('nit', 30)->nullable();
            $table->string('nrc', 30)->nullable();
            $table->string('dui', 20)->nullable();
            $table->string('economic_activity', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->boolean('iva_taxpayer')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique('nrc');
            $table->index('nit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
