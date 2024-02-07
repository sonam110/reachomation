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
        Schema::create('semrush_history_us', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('domain_id');
            $table->string('website', 100)->index('website');
            $table->integer('sok');
            $table->integer('sot');
            $table->date('semrush_date');
            $table->date('checkedon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('semrush_history_us');
    }
};
