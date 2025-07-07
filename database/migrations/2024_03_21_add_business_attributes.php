<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->integer('seating_count')->nullable()->comment('Počet sedení');
            $table->integer('ice_cream_flavors_count')->nullable()->comment('Počet druhov zmrzliny');
            $table->boolean('has_additional_products')->default(false)->comment('Má sprievodny tovar?');
            $table->decimal('ice_cream_price', 8, 2)->nullable()->comment('Cena zmrzliny v EUR');
            $table->json('business_hours')->nullable()->comment('Otváracie hodiny');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('seating_count');
            $table->dropColumn('ice_cream_flavors_count');
            $table->dropColumn('has_additional_products');
            $table->dropColumn('ice_cream_price');
            $table->dropColumn('business_hours');
        });
    }
}; 