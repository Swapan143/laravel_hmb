<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vehicles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
    
            $table->string('vehicle_no', 191);
            $table->string('vehicle_name', 191)->nullable();
            $table->string('vehicle_chassis_no', 191)->nullable();
            $table->integer('transporter_id')->nullable()->comment("fk: transporters");
            $table->integer('client_id')->nullable()->comment("fk: clients");
            $table->integer('vehicle_siding_location')->nullable();
            $table->string('driver_name', 191)->nullable();
            $table->string('engine_number', 191)->nullable();
            $table->string('rfid_number', 100)->nullable();
            $table->date('vehicle_added_on')->nullable();
            $table->string('image', 191)->nullable();

            $table->tinyInteger('status')->default('1')->comment("1 => Active, 2 => Inactive");
        
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_vehicles');
    }
}
