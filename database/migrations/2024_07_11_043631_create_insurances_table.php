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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id')->nullable(); // Make this nullable for onDelete('set null')
            $table->string('claim_number')->unique();
            $table->string('insurance_type');
            $table->enum('status', ['claimed', 'approved', 'rejected', 'pending']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('description');
            $table->date('claim_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('rejection_date')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('insurance_document')->nullable();
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('set null');
            $table->foreign('serial_number')->references('serial_number')->on('assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
