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
        Schema::create('tbl_diesels', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('sl_no', 50)->comment("System Generated ID");
            $table->integer('user_id')->nullable()->comment("fk: users");
            $table->dateTime('date_time');
            $table->integer('vehicle_id')->comment("fk: vehicles");
            $table->integer('transporter_id')->comment("fk: transporters");
            $table->decimal('quantity', 10, 2)->comment("In Litres");
            $table->decimal('total_amount', 10, 2);
            $table->text('location')->nullable()->comment("GPS");
            $table->string('latitude', 150)->comment("GPS");
            $table->string('longitude', 150)->comment("GPS");
            $table->text('remarks')->nullable();
            $table->text('image')->nullable();
            $table->tinyInteger('status')->default('1')->comment("1 => Active, 2 => Inactive");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_diesels');
    }
};
