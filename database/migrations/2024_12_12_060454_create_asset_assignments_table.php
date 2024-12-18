<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade'); // Reference to assets table
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade'); // Reference to users table (user to whom the asset is assigned)
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade'); // Reference to users table (who assigned the asset)
            $table->timestamp('assigned_at')->useCurrent(); // The date and time the asset was assigned
            $table->enum('status', ['assigned', 'reassigned'])->default('assigned'); // Status of the assignment
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_assignments');
    }
}
