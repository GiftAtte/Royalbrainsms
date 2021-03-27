<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->integer('report_id');
            $table->integer('subject_id')->nullable();
            $table->integer('student_id');
            $table->string('test1')->nullable();
            $table->string('test2')->nullable();
            $table->string('test3')->nullable();
            $table->string('exams')->nullable();
            $table->string('total')->nullable();
            $table->string('average')->nullable();
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
        Schema::dropIfExists('marks');
    }
}
