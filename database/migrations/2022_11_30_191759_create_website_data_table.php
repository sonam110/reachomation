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
        Schema::create('website_data', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('domain_id');
            $table->string('website', 255)->unique('website_unique');
            $table->text('title');
            $table->text('description');
            $table->text('anchor');
            $table->string('author', 255);
            $table->integer('da')->default(0);
            $table->integer('tf')->default(0);
            $table->integer('sot');
            $table->integer('sok');
            $table->string('ext', 5);
            $table->integer('score');
            $table->text('emails');
            $table->text('facebook_url');
            $table->text('twitter_url');
            $table->text('youtube_url');
            $table->text('instagram_url');
            $table->text('linkedin_url');
            $table->text('pinterest_url');
            $table->date('creation_date');
            $table->text('web');
            $table->integer('blog_status');
            $table->date('activity_date');
            $table->date('added_date');
            $table->boolean('autotag_status')->default(false);
            $table->integer('credit_cost')->default(0);
            $table->text('tags');
            $table->boolean('tag_category_status')->default(false);
            $table->text('tag_category');
            $table->text('cat_percentile_1');
            $table->text('cat_percentile_5');
            $table->text('cat_percentile_10');
            $table->boolean('percentile_status')->default(false);
            $table->integer('domain_age')->nullable();
        });
        
        \DB::statement('ALTER TABLE website_data ADD FULLTEXT search(title, description,anchor,website)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_data');
    }
};
