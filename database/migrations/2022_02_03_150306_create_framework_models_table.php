<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrameworkModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('framework_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('userId');
            $table->integer('langId');
            $table->string('creator');
            $table->string('logo');
            $table->text('description');
            $table->string('tags');
            $table->string('github');
            $table->string('website');
            $table->string('twitter');
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
        Schema::dropIfExists('framework_models');
    }
}
