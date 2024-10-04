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
            
            $table->unsignedBigInteger('user_id');
            $table->string('claim_number')->unique();
            $table->string('insurance_type');
            $table->enum('status', ['claimed', 'approved', 'rejected', 'pending']);
            $table->text('description');
            $table->date('claim_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('rejection_date')->nullable();
           
            $table->string('insurance_document')->nullable();
            
            $table->timestamps();

           
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
