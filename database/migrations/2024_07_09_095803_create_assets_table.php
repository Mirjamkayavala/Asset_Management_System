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
        Schema::create('assets', function (Blueprint $table) {
            $table->id(); 
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->unique()->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('asset_number')->unique()->nullable(); 
            $table->string('category')->nullable();
            $table->string('vendor')->nullable();
            $table->string('location')->nullable();
            $table->date('date');
            $table->unsignedBigInteger('previous_user_id')->nullable(); 
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->unsignedBigInteger('location_id')->nullable(); 
            $table->unsignedBigInteger('vendor_id')->nullable(); 
            $table->unsignedBigInteger('insurance_id')->nullable(); 
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();

            // Define foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('previous_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('asset_categories');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('insurance_id')->references('id')->on('insurances');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
