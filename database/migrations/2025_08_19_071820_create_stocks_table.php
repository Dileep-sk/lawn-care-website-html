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
            $table->string('mark_no')->index();
            $table->string('design_no')->index();
            $table->string('item_name')->index();
            $table->integer('quantity')->index();
            $table->string('message')->nullable()->index();
            $table->tinyInteger('status')->default(1)->comment("Active = 1, Inactive = 0");
            $table->timestamps();
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
