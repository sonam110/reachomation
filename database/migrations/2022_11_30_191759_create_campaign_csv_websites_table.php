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
        Schema::create('campaign_csv_websites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('camp_id');
            $table->unsignedBigInteger('user_id');
            $table->string('website')->nullable();
            $table->tinyInteger('is_blog')->default(0)->comment('1=true, 2=false');
            $table->string('email')->nullable();
            $table->tinyInteger('is_email_valid')->default(0)->comment('1=true, 2=false');
            $table->string('da')->nullable();
            $table->string('sot')->nullable();
            $table->string('sok')->nullable();
            $table->string('author')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('is_blog_judgement')->default(0)->comment('0=No,1=Yes,3=NotFound,5=WIP,4=Done');
            $table->tinyInteger('is_email_extract')->default(0)->comment('0=No,1=Yes,2=Done');
            $table->tinyInteger('is_email_validate')->default(0)->comment('0=No,1=Yes,2=Done');
            $table->tinyInteger('is_personalize_data')->default(0)->comment('0=No,1=Yes,2=Done');
            $table->tinyInteger('is_compute_semrus_da')->default(0)->comment('0=No,1=Yes,2=SM Done,4=DA  also Done');
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=Complete');
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
        Schema::dropIfExists('campaign_csv_websites');
    }
};
