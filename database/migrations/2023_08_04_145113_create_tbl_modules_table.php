<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_modules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->integer('parent_id')->comment("fk: roles");
            $table->string('module_name', 191);
            $table->string('module_url', 191)->nullable();
            $table->string('module_icon', 191)->nullable();
            $table->integer('order_id');
            $table->string('controller_name', 191)->nullable();
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
        Schema::dropIfExists('tbl_modules');
    }
}
