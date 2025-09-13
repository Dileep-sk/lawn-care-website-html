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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('banner_title')->nullable();
            $table->text('banner_short_description')->nullable();
            $table->string('service_title')->nullable();
            $table->text('service_description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('Active => 1 | Inactive => 0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
