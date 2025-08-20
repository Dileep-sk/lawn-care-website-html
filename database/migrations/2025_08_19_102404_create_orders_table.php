<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('order_no')->unique();
            $table->date('date');
            $table->string('broker_name')->nullable();
            $table->string('transport_company')->nullable();
            $table->string('design_no');
            $table->string('item_name');
            $table->integer('quantity');
            $table->decimal('rate', 10, 2);
            $table->tinyInteger('status')->default(0)->comment("Active = 1, Inactive = 0");
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
