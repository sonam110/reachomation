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
        Schema::create('campaign_followups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->unsignedBigInteger('camp_id');
            $table->unsignedBigInteger('list_id')->nullable();
            $table->unsignedBigInteger('temp_id')->nullable();
            $table->string('from_email')->nullable();
            $table->string('file_csv')->nullable();
            $table->string('list_to_file_csv')->nullable();
            $table->string('final_upload_csv')->nullable();
            $table->string('final_upload_csv_count')->nullable();
            $table->string('file_mail_clm_name')->nullable()->comment('if file upload then enter csv email column');
            $table->enum('is_file_read', ['Y', 'N'])->default('Y');
            $table->integer('total_domain')->default(0);
            $table->integer('credit_deduct');
            $table->string('subject')->nullable();
            $table->longText('message')->nullable();
            $table->text('daysofweek')->nullable();
            $table->integer('dailylimit')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('starttime')->nullable();
            $table->timestamp('endtime')->nullable();
            $table->string('cooling_period')->nullable();
            $table->string('timezone')->nullable();
            $table->string('interval')->nullable();
            $table->tinyInteger('is_completed')->default(0);
            $table->integer('total_delivered')->default(0);
            $table->integer('total_failed')->default(0);
            $table->integer('total_open')->default(0);
            $table->integer('total_click')->default(0);
            $table->integer('total_reply')->default(0);
            $table->enum('is_resend', ['N', 'Y'])->default('N');
            $table->enum('is_credit_deduct', ['N', 'Y'])->default('N');
            $table->timestamp('credit_back_date')->nullable();
            $table->date('followup_send_date');
            $table->tinyInteger('type')->nullable()->comment('1:list,2:csv');
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=In-process, 2=In-progress,3=Ready-to-complete,4=Completed,5:stop,6:draft');
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
        Schema::dropIfExists('campaign_followups');
    }
};
