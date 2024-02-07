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
        Schema::create('outlook_auth_users', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('user_id');
            $table->string('email', 255)->nullable();
            $table->string('picture', 255)->nullable();
            $table->longText('accesstoken');
            $table->longText('refreshtoken');
            $table->string('expired_in', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlook_auth_users');
    }
};
