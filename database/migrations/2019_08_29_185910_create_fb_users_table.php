<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fbId');
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('fb_users');
    }
}
