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
            $table->string('category')->nullable();
            $table->string('current_user')->nullable();
            $table->string('date')->nullable();
            $table->string('previous_user')->nullable();
            $table->string('vendor')->nullable();
            $table->string('status')->nullable();
            $table->timestamps(); // Created at and updated at timestamps

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
