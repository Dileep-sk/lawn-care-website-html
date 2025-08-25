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
        Schema::create('order_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('mark_no_id')->index();
            $table->unsignedBigInteger('design_no_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->integer('quantity')->index();
            $table->unsignedBigInteger('order_no_id')->index();
            $table->tinyInteger('status')->index();
            for ($i = 1; $i <= 8; $i++) {
                $table->string("matching_$i")->nullable();
            }
            $table->string('message')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mark_no_id')->references('id')->on('mark_nos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('design_no_id')->references('id')->on('design_nos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('order_no_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
    