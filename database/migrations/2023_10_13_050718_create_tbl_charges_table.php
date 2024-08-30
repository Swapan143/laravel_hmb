<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_charges', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->integer('location_id')->comment("fk: location");
            $table->string('location_name', 191);
            $table->integer('client_id')->comment("fk: client");
            $table->string('client_name', 191);
            $table->string('year', 191)->nullable();
            $table->string('month', 191)->nullable();
            $table->string('year_month', 191)->nullable();
            $table->decimal('tanker_fare', 10, 2)->default(0)->comment("Rate (in Rs.)");
            $table->decimal('freight_charge', 10, 2)->default(0)->comment("Per MT Rate (in Rs.)");
            $table->decimal('diesel_price', 10, 2)->default(0)->comment("Diesel(HSD) Price per Litre");
            $table->decimal('accidental_rate', 10, 2)->default(0);
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
        Schema::dropIfExists('tbl_charges');
    }
}
