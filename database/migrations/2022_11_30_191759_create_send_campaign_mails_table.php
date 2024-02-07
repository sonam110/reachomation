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
        Schema::create('send_campaign_mails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('camp_id');
            $table->unsignedBigInteger('followup_id')->nullable();
            $table->string('msg_id', 255)->nullable();
            $table->string('threadId')->nullable();
            $table->string('from_email')->nullable();
            $table->string('to_email')->nullable();
            $table->text('subject')->nullable();
            $table->longText('message')->nullable();
            $table->integer('stage')->nullable()->default(1);
            $table->tinyInteger('is_open')->default(0);
            $table->tinyInteger('is_click')->default(0);
            $table->tinyInteger('is_reply')->default(0);
            $table->string('website')->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('is_unsubscribe')->default(0);
            $table->tinyInteger('level')->default(1);
            $table->timestamp('mail_send_date')->nullable();
            $table->timestamp('default_timezone_date')->nullable();
            $table->timestamp('is_open_time')->nullable();
            $table->timestamp('last_reply_time')->nullable();
            $table->timestamp('last_click_time')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=Success, 2=Blacklist,3=Bounc,4:Invalid Email,5:Process');
            $table->tinyInteger('type')->nullable();
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
        Schema::dropIfExists('send_campaign_mails');
    }
};
