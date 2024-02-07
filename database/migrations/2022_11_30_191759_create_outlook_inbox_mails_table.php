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
        Schema::create('outlook_inbox_mails', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('user_id');
            $table->integer('auth_id');
            $table->longText('messageid');
            $table->string('Date', 255);
            $table->string('mailcreate_date', 255);
            $table->string('date_timestamp', 255);
            $table->string('mail_from', 255);
            $table->longText('subject');
            $table->longText('body')->nullable();
            $table->integer('has_attchment');
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
        Schema::dropIfExists('outlook_inbox_mails');
    }
};
