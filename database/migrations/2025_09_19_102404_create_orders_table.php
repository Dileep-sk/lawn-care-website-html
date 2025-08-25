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
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('order_no')->unique()->index();
            $table->date('date');
            $table->unsignedBigInteger('broker_id')->nullable()->index();
            $table->unsignedBigInteger('transport_company_id')->nullable()->index();
            $table->unsignedBigInteger('mark_no_id')->index();
            $table->unsignedBigInteger('design_no_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->integer('quantity')->index();
            $table->decimal('rate', 10, 2)->index();
            $table->tinyInteger('status')->default(0)->comment("Active = 1, Inactive = 0");
            $table->string('message')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('broker_id')->references('id')->on('brokers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transport_company_id')->references('id')->on('transport_companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mark_no_id')->references('id')->on('mark_nos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('design_no_id')->references('id')->on('design_nos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
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
