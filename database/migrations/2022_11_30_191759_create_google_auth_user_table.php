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
        Schema::create('google_auth_user', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('user_id')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('picture', 255)->nullable();
            $table->longText('accesstoken');
            $table->longText('refreshtoken');
            $table->string('expired_in', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_auth_user');
    }
};
