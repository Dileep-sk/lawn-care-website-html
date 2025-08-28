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
        Schema::create('order_jobs_images', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('job_id')->index();
            $table->foreign('job_id')->references('id')->on('order_jobs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_jobs_images');
    }
};
