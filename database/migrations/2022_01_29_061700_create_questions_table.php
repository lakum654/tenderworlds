<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('question_id');
            $table->longText('title');
            $table->longText('body');
            $table->text('question_link');
            $table->text('tags');
            $table->boolean('answer_status')->comment('1-done,0-pendding')->default(0);
            $table->boolean('sitemap')->default(0)->comment('0-pendding,1-done');
            $table->string('slug');
            $table->bigInteger('created_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('questions');
    }
}
