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
        Schema::create('appsettings', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('app_name')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('mobilenum')->nullable();
            $table->decimal('tax_percentage', 4)->default(18);
            $table->text('seo_keyword')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('google_analytics')->nullable();
            $table->text('fallback_text')->nullable();
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
        Schema::dropIfExists('appsettings');
    }
};
