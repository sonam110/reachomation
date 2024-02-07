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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('is_parent')->nullable();
            $table->bigInteger('top_most_parent')->nullable();
            $table->string('name');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('list_id')->nullable();
            $table->bigInteger('mail_account_id');
            $table->bigInteger('campid')->nullable()->comment('Data Import from other  old Email campaign');
            $table->unsignedBigInteger('temp_id')->nullable();
            $table->string('from_email')->nullable();
            $table->string('file_csv')->nullable();
            $table->string('list_to_file_csv')->nullable();
            $table->string('final_upload_csv')->nullable();
            $table->string('final_upload_csv_count')->nullable();
            $table->string('file_mail_clm_name')->nullable()->comment('if file upload then enter csv email column');
            $table->enum('is_file_read', ['Y', 'N'])->default('N');
            $table->integer('total_domain')->default(0);
            $table->integer('stage')->nullable()->default(1);
            $table->float('credit_deduct', 10, 0)->nullable();
            $table->string('features')->nullable();
            $table->tinyInteger('including_non_blog')->default(0)->comment('1:Yes,0:No');
            $table->longText('subject')->nullable();
            $table->longText('message')->nullable();
            $table->longText('fallback_text')->nullable();
            $table->text('daysofweek')->nullable();
            $table->integer('dailylimit')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('starttime')->nullable();
            $table->time('endtime')->nullable();
            $table->date('timezone_start_date')->nullable();
            $table->string('cooling_period')->nullable();
            $table->string('timezone')->nullable();
            $table->string('interval')->nullable();
            $table->tinyInteger('is_completed')->default(0);
            $table->integer('total_delivered')->default(0);
            $table->integer('total_failed')->default(0);
            $table->integer('total_open')->default(0);
            $table->integer('total_click')->default(0);
            $table->integer('total_reply')->default(0);
            $table->integer('copy_count')->default(1);
            $table->integer('total_contact')->nullable()->default(0);
            $table->integer('import_contact')->nullable()->default(0);
            $table->integer('invalid_contact')->nullable()->default(0);
            $table->integer('duplicate_contact')->nullable()->default(0);
            $table->enum('is_resend', ['N', 'Y'])->default('N');
            $table->enum('is_credit_deduct', ['N', 'Y'])->default('N');
            $table->timestamp('credit_back_date')->nullable();
            $table->timestamp('last_mail_send_date')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1:list,2:csv,3:old campiagn');
            $table->date('campaign_send_date')->nullable();
            $table->text('reason')->nullable();
            $table->tinyInteger('account_type')->nullable()->comment('1:Gmail,2:OutLook');
            $table->tinyInteger('is_feature')->nullable()->default(0);
            $table->tinyInteger('attempt_type')->nullable()->comment('1:campaign,2:Followup');
            $table->integer('attemp')->nullable()->default(1);
            $table->integer('bounce_count')->nullable()->default(5);
            $table->tinyInteger('is_followup')->default(0);
            $table->tinyInteger('is_attempt')->nullable()->comment('1:yes,0:No');
            $table->tinyInteger('non_stop')->default(0)->comment('1:yes,0:No');
            $table->tinyInteger('followup_condition')->default(3)->comment('1:Not Opend,2:Not Reply,3:Regardless');
            $table->string('domain_authority')->nullable();
            $table->string('semrus_traffic')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=In-process, 2=In-progress,3=Ready-to-complete,4=Completed,5:stop,6:draft,7:Wait for csv creation in case of tool integration');
            $table->tinyInteger('is_active')->default(0)->comment('1:Current active stage,0:No active stage');
            $table->tinyInteger('is_same_thread')->default(0);
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
        Schema::dropIfExists('campaigns');
    }
};
