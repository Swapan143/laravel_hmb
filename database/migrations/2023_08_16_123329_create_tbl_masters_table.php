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
        Schema::create('tbl_masters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->decimal('tanker_fare', 10, 2)->default(0)->comment("Rate (in Rs.)");
            $table->decimal('freight_charge', 10, 2)->default(0)->comment("Per MT Rate (in Rs.)");
            $table->decimal('diesel_price', 10, 2)->default(0)->comment("Diesel(HSD) Price per Litre");
            $table->decimal('accidental_rate', 10, 2)->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_masters');
    }
};
