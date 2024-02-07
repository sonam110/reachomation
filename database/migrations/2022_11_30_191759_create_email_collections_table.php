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
        Schema::create('email_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('name', 255)->nullable();
            $table->string('email', 255);
            $table->string('eid', 255)->nullable();
            $table->string('picture')->nullable();
            $table->longText('accesstoken');
            $table->longText('refreshtoken');
            $table->string('expired_in')->nullable();
            $table->integer('daily_limit')->default(1500);
            $table->boolean('status')->default(true)->comment('0-Inactive, 1- Active');
            $table->tinyInteger('reconnect')->default(0)->comment('0-No, 1-Yes');
            $table->boolean('account_type')->comment('1-Gmail, 2- Microsoft');
            $table->tinyInteger('is_default')->nullable()->default(0);
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
        Schema::dropIfExists('email_collections');
    }
};
