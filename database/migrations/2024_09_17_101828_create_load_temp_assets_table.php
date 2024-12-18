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
        Schema::create('load_temp_assets', function (Blueprint $table) {
            $table->id();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_number')->nullable();
            // $table->string('category')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('date')->nullable();
            $table->unsignedBigInteger('previous_user_id')->nullable();
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->string('vendor')->nullable();
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->unsignedBigInteger('location_id')->nullable(); 
            // $table->unsignedBigInteger('vendor_id')->nullable(); 
            $table->string('facility')->nullable();
            $table->string('status')->nullable();
            $table->timestamps(); 

            // Define foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('previous_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('asset_categories');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('location_id')->references('id')->on('locations');
            // $table->foreign('vendor_id')->references('id')->on('vendors');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('load_temp_assets');
    }
};
