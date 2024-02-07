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
        Schema::create('outlook_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('user_id');
            $table->integer('auth_id');
            $table->string('from_mail', 255);
            $table->string('to_mail', 255)->nullable();
            $table->string('cc_mail', 255)->nullable();
            $table->string('bcc_mail', 255)->nullable();
            $table->string('subject', 255)->nullable();
            $table->longText('body')->nullable();
            $table->integer('has_attachment');
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
        Schema::dropIfExists('outlook_details');
    }
};
