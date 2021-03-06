<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftbricksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('softbricks', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->unique();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('token')->nullable();
            $table->string('badnum')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('softbricks');
    }
}
