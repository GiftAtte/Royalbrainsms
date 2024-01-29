<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount',65,2);
            $table->decimal('discounted_amount',65,2);
            $table->json('product_details');
            $table->unsignedBigInteger('sales_agent');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('invoice_number')->nullable();
            $table->decimal('discount',65,2)->default(0.00);
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
