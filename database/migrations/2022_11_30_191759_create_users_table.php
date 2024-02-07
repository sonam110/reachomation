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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('company')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->string('oauth_id', 255)->nullable();
            $table->string('oauth_provider', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->text('avatar')->nullable();
            $table->text('niches')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('line1')->nullable();
            $table->tinyInteger('is_email_notify')->default(1);
            $table->string('skype')->nullable();
            $table->string('mailbox_email', 255)->nullable();
            $table->string('mailbox_password', 255)->nullable();
            $table->tinyInteger('mailbox_type')->nullable();
            $table->string('mailbox_url', 255)->nullable();
            $table->tinyInteger('is_mailbox_connected')->nullable();
            $table->double('credits', 8, 2)->default(0);
            $table->tinyInteger('plan')->nullable()->comment('0=null, 1=free forever, 2=starter,3=business');
            $table->string('duration', 255)->nullable();
            $table->tinyInteger('status')->comment('1=Active, 2=Inactive,3=Incomplete,4=Delete');
            $table->timestamp('plan_started_at')->nullable();
            $table->timestamp('plan_expired_at')->nullable();
            $table->tinyInteger('dont_show')->nullable();
            $table->integer('default_tid')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->string('stripe_id', 255)->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('pm_type', 255)->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->integer('user_interest')->nullable();
            $table->string('way_of_support')->nullable();
            $table->string('password_token')->nullable();
            $table->string('email_verified_token')->nullable();
            $table->string('default_card')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
