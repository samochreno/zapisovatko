<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('business_records', function (Blueprint $table) {
            $table->string('seating_status')->nullable();
            $table->string('visitor_type')->nullable();
            $table->json('additional_purchases')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_records', function (Blueprint $table) {
            $table->dropColumn('seating_status');
            $table->dropColumn('visitor_type');
            $table->dropColumn('additional_purchases');
        });
    }
};
