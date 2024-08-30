<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->integer('role_id')->comment("fk: roles");
            $table->string('first_name', 191);
            $table->string('last_name', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('country_code', 50)->nullable();
            $table->string('mobile', 50);
            $table->string('password', 191)->nullable();
            $table->tinyInteger('type')->default('0')->comment("1 => Admin, 2 => Challan, 3 => Diesel, 4 => ChallanAndDiesel");
            $table->tinyInteger('status')->default('1')->comment("1 => Active, 2 => Inactive");
            $table->string('otp', 10)->nullable();
            $table->string('image', 191)->nullable();
            $table->text('location')->nullable();
            $table->dateTime('otp_created_at')->nullable();

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
        Schema::dropIfExists('tbl_users');
    }
}
