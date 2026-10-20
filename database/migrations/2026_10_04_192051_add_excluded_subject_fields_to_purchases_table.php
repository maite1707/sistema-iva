<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->boolean('is_excluded_subject')->default(false)->after('total');
            $table->decimal('retention_amount', 12, 2)->nullable()->after('is_excluded_subject');
            $table->string('renta_operation_type', 100)->nullable()->after('retention_amount');
            $table->string('renta_classification', 100)->nullable()->after('renta_operation_type');
            $table->string('renta_sector', 100)->nullable()->after('renta_classification');
            $table->string('renta_cost_type', 100)->nullable()->after('renta_sector');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'is_excluded_subject',
                'retention_amount',
                'renta_operation_type',
                'renta_classification',
                'renta_sector',
                'renta_cost_type',
            ]);
        });
    }
};