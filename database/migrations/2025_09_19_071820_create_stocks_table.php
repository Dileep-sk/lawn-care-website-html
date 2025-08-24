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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mark_no_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('design_no_id')->index();
            $table->integer('quantity')->index();
            $table->string('message')->nullable()->index();
            $table->tinyInteger('status')->default(1)->comment("Active = 1, Inactive = 0");
            $table->tinyInteger('stock_manage')->default(1)->comment("Add = 1, Remove = 0");
            $table->timestamps();
            $table->foreign('mark_no_id')->references('id')->on('mark_nos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('design_no_id')->references('id')->on('design_nos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
