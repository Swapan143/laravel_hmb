<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblActionPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_action_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('module_id');
            $table->integer('role_id');
            $table->string('permission', 11)->nullable();
            $table->tinyInteger('status')->default('1')->comment("1 => Active, 2 => Inactive, 3 => Suspended");
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
        Schema::dropIfExists('tbl_action_permissions');
    }
}
