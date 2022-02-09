<?php

/*
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_models', function (Blueprint $table) {
            $table->id();
            $table->text('about');
            $table->text('imprint');
            $table->text('tos');
            $table->string('reg_info');
            $table->string('cookie_consent');
            $table->text('head_code');
            $table->string('newsletter_token')->nullable();
            $table->string('newsletter_subject')->nullable();
            $table->text('newsletter_content')->nullable();
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
        Schema::dropIfExists('app_models');
    }
}
