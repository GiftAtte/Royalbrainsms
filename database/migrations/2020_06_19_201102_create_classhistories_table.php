<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClasshistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classhistories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name');
            $table->date('dob');
            $table->string('gender');
            $table->string('contact_adress');
            $table->string('nationality');
            $table->string('state');
            $table->string('lga');
            $table->integer('arm_id');
            $table->integer('phone');
            $table->string('blood_groop');
            //$table->string('Password');
           // $table->string('Image');
            $table->string('reg_date');
            $table->integer('roll_no');
            $table->string('class_id');
           // $table->string('photo')->default('profile.png');
            $table->rememberToken();
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
        Schema::dropIfExists('classhistories');
    }
}
