<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('series');
            $table->unsignedInteger('repetitions');
            $table->boolean('standalone');
            $table->unsignedBigInteger('format_id')->index();
            $table->string('file')->nullable();
            $table->string('video_length')->nullable();
            $table->unsignedInteger('views');
            $table->boolean('published');
            $table->timestamps();

            $table->foreign('format_id')->references('id')->on('formats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
