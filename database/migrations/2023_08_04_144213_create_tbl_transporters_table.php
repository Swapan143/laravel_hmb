<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTransportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transporters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
    
            $table->string('transporter_name', 191);
            $table->string('company_name', 191)->nullable();
            
            $table->string('mobile', 50);
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('sms')->default('0')->comment("1 => Active, 0 => Inactive");
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
        Schema::dropIfExists('tbl_transporters');
    }
}
