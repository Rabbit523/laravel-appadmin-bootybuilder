<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('amount_weeks_program');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('subscribed');
            $table->boolean('has_timer')->default(false);
            $table->string('productId')->nullable();
            $table->string('file')->nullable();
            $table->boolean('published');
            $table->unsignedInteger('level1_work')->default(30);
            $table->unsignedInteger('level1_rest')->default(30);
            $table->unsignedInteger('level1_rounds')->default(3);
            $table->unsignedInteger('level2_work')->default(30);
            $table->unsignedInteger('level2_rest')->default(30);
            $table->unsignedInteger('level2_rounds')->default(3);
            $table->unsignedInteger('level3_work')->default(30);
            $table->unsignedInteger('level3_rest')->default(30);
            $table->unsignedInteger('level3_rounds')->default(3);
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
        Schema::dropIfExists('workouts');
    }
}
