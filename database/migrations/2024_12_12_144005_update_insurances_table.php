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
        Schema::table('insurances', function (Blueprint $table) {
            $table->string('written_off_source')->nullable()->after('serial_number'); // To store "Internal" or "External"
            $table->unsignedBigInteger('last_user_id')->nullable()->after('written_off_source');

            $table->foreign('last_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurances', function (Blueprint $table) {
            $table->dropColumn('written_off_source');
            $table->dropForeign(['user_id']); // Drop the foreign key before dropping the column
            $table->dropColumn('last_user_id');
        });
    }
};
