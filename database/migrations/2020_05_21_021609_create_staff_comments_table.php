<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_comments', function (Blueprint $table) {
            $table->id();
            $table->float('lower_bound');
            $table->float('upper_bound');
            $table->string('comment');
            $table->integer('school_id');
            $table->integer('level_id');
            $table->string('type')->default('general');
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
        Schema::dropIfExists('staff_comments');
    }
}
