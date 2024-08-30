<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_clients', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
    
            $table->string('company_name', 191);
            $table->string('mobile', 50);
            $table->string('address', 191)->nullable();
            $table->string('gst', 191)->nullable();
            $table->string('challan_format', 191)->nullable();
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
        Schema::dropIfExists('tbl_clients');
    }
}
