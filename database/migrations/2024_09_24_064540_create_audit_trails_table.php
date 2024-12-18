<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User who made the change
            $table->string('table_name');          // Name of the table where the change occurred
            $table->string('column_name')->nullable();         // Name of the column that was changed
            $table->text('old_value')->nullable(); // Old value before the change
            $table->text('new_value')->nullable(); // New value after the change
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->string('action');              // Action performed (create, update, delete)
            $table->timestamps();                  // Timestamps for created_at and updated_at

            // Foreign key constraint for the user who made the change
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_trails');
    }
}
