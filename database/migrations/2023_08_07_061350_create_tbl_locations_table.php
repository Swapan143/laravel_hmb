<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_locations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->integer('client_id')->comment("fk: clients");
            $table->string('location_name', 191);
            $table->tinyInteger('location_type')->default('1')->comment("1 => Loading, 2 => Sending");
            $table->decimal('freight_charge', 10, 2);
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
        Schema::dropIfExists('tbl_locations');
    }
}
