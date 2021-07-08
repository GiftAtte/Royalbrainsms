<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrechestudentDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crechestudent_domains', function (Blueprint $table) {
            $table->id();
            $table->integer('domain_id');
            $table->integer('subdomain_id');
            $table->integer('rating_id');
            $table->integer('student_id');
            $table->integer('report_id');
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
        Schema::dropIfExists('crechestudent_domains');
    }
}
