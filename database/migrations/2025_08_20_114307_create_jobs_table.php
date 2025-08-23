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
            $table->string('customer_name')->index();
            $table->string('design_no')->index();;
            $table->string('image')->nullable();
            $table->integer('quantity')->index();
            $table->string('order_no')->index();
            $table->tinyInteger('status')->index();
            for ($i = 1; $i <= 8; $i++) {
                $table->string("matching_$i")->nullable();
            }
            $table->string('message')->nullable();
            $table->timestamps();
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
