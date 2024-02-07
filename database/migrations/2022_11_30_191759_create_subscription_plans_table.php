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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 45)->comment('Name of Plan');
            $table->string('slug')->nullable();
            $table->double('price', 8, 2)->default(0);
            $table->float('monthly_price', 10, 0)->default(0);
            $table->integer('credits')->default(0);
            $table->integer('parallel_users')->nullable();
            $table->string('size_limit')->nullable();
            $table->string('templates')->nullable();
            $table->text('description')->nullable();
            $table->string('stripe_plan_id')->nullable();
            $table->boolean('plan_type')->default(true)->comment('1-Monthly, 2- Annual');
            $table->tinyInteger('import_sites')->default(0);
            $table->boolean('site_features')->default(true)->comment('0-No, 1- Yes');
            $table->boolean('geo_locations')->default(true)->comment('0-No, 1- Yes');
            $table->boolean('customer_support')->default(true)->comment('chat sype support');
            $table->boolean('chrome_addon')->default(true)->comment('0-No, 1- Yes');
            $table->boolean('traffic_history')->default(true)->comment('0-No, 1- Yes');
            $table->boolean('automation')->default(false)->comment('0-No, 1- Yes');
            $table->string('mail_connect')->default('0')->comment('0-No, 1- Yes');
            $table->boolean('reporting')->default(false)->comment('0-No, 1- Yes');
            $table->tinyInteger('buy_more_credit')->default(0);
            $table->boolean('status')->default(true)->comment('0-Inactive, 1- Active');
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
        Schema::dropIfExists('subscription_plans');
    }
};
