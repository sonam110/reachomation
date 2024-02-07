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
        Schema::create('feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('camp_id');
            $table->unsignedBigInteger('internal_id')->nullable();
            $table->string('msg_id')->nullable();
            $table->string('from_email')->nullable();
            $table->string('to_email')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('c_date')->nullable()->comment('Open Reply click date');
            $table->tinyInteger('status')->default(0)->comment('1=Open,2:Reply,3=Click');
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
        Schema::dropIfExists('feeds');
    }
};
