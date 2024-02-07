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
        Schema::create('blogsemrush_us_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('domain_id');
            $table->string('website', 100)->index('website');
            $table->integer('status');
            $table->integer('sok');
            $table->integer('sot');
            $table->date('semrush_date');
            $table->date('createdat');
            $table->boolean('indexed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogsemrush_us_history');
    }
};
