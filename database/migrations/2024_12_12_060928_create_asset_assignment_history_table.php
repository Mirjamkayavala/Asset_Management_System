<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetAssignmentHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('asset_assignment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->string('change_type'); // 'assignment' or 'update'
            $table->json('changes')->nullable(); // Stores the changed data in JSON format
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_assignment_history');
    }
}
