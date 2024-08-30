<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVehiclesLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vehicles_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
    
            $table->integer('vehicle_id')->nullable()->comment("fk: vehicle");
            $table->integer('client_id')->nullable()->comment("fk: clients");
            

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
        Schema::dropIfExists('tbl_vehicles_logs');
    }
}
