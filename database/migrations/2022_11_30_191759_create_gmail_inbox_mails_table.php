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
        Schema::create('gmail_inbox_mails', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('user_id');
            $table->integer('auth_id');
            $table->longText('messageid');
            $table->string('deliver_to', 255);
            $table->string('Date', 255);
            $table->string('mailcreate_date', 255);
            $table->string('date_timestamp', 255);
            $table->string('mail_From', 255);
            $table->longText('subject')->nullable();
            $table->longText('body')->nullable();
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
        Schema::dropIfExists('gmail_inbox_mails');
    }
};
