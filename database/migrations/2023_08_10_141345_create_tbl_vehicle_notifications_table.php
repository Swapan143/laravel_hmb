<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVehicleNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vehicle_notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->integer('vehicle_id')->nullable()->comment("fk: tbl_vehicles");
           
            $table->date('permit_valid_date')->nullable();
            $table->date('tax_valid_date')->nullable();
            $table->date('fitness_valid_date')->nullable();
            $table->date('insurance_valid_date')->nullable();
         
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
        Schema::dropIfExists('tbl_vehicle_notifications');
    }
}
