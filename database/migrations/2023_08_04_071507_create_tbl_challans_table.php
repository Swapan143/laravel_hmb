<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    use SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tbl_challans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('sl_no', 50)->comment("System Generated ID");
            $table->integer('user_id')->nullable()->comment("fk: users");
            $table->integer('client_id')->nullable()->comment("fk: clients");
            $table->integer('vehicle_id')->nullable()->comment("fk: vehicles");
            $table->date('challan_date')->nullable();
            $table->string('challan_no', 191)->nullable();
            $table->integer('loading_location_id')->nullable()->comment("fk: locations");
            $table->integer('siding_location_id')->nullable()->comment("fk: locations");
            $table->string('tare_weight', 50)->nullable()->comment("in MT");
            $table->string('gross_weight', 50)->nullable()->comment("in MT");
            $table->string('net_weight', 50)->nullable()->comment("in MT");
            $table->text('location')->nullable()->comment("GPS");
            $table->string('latitude', 150)->comment("GPS");
            $table->string('longitude', 150)->comment("GPS");
            $table->text('remarks')->nullable();
            $table->string('image', 191)->nullable();
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
        Schema::dropIfExists('tbl_challans');
    }
};
