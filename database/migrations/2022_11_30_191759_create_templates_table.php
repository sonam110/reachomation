<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->comment('1=default, 2=userdefined');
            $table->integer('user_id');
            $table->string('name', 255)->nullable();
            $table->longText('subject')->nullable();
            $table->text('body')->nullable();
            $table->tinyInteger('status')->comment('1=Active, 2=Delete');
            $table->text('fallback_text')->nullable();
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
        Schema::dropIfExists('templates');
    }
};
