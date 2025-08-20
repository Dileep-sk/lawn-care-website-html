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
            $table->string('customer_name')->index();
            $table->string('order_no')->unique()->index();
            $table->date('date');
            $table->string('broker_name')->nullable()->index();
            $table->string('transport_company')->nullable()->index();
            $table->string('design_no')->index();
            $table->string('item_name')->index();
            $table->integer('quantity')->index();
            $table->decimal('rate', 10, 2)->index();
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
