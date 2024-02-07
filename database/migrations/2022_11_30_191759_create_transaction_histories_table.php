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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->integer('user_id');
            $table->tinyInteger('bal_type')->comment('1=credit,2=debit');
            $table->tinyInteger('payment_for')->default(1)->comment('1:credit purchase,2:subscription,3:topup');
            $table->double('old_credits', 8, 2)->default(0);
            $table->double('credits', 8, 2)->nullable()->default(0);
            $table->float('price', 10, 0)->default(0);
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->comment('1=Success, 2=Failed, 3=Pending');
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
        Schema::dropIfExists('transaction_histories');
    }
};
