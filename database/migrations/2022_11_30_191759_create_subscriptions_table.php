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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('stripe_id');
            $table->integer('plan_id')->nullable();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('subscription_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('canceled_date')->nullable();
            $table->tinyInteger('is_canceled')->default(0);
            $table->text('hosted_invoice_url')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(3)->comment('1=Success, 2=Failed, 3=Pending,4:Cancelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
