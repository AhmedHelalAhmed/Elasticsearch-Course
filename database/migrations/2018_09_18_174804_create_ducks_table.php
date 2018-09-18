<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ducks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('age');
            $table->string('color');
            $table->string('gender');
            $table->string('about')->nullable();
            $table->boolean('funkyDuck')->default(false);
            $table->string('home town');
            $table->date('registered');
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
        Schema::dropIfExists('ducks');
    }
}
