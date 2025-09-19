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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->text('title'); // For TUI Calendar title
            $table->text('appointment_image')->nullable();
            $table->text('description');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->unsignedSmallInteger('slots')->default(0);
            $table->unsignedSmallInteger('slots_taken')->default(0);
            $table->boolean('is_all_day')->default(false); // Supports all-day events
            $table->enum('category', ['time', 'milestone'])->default('time'); // Required for TUI Calendar
            $table->string('color', 20)->default('#04050c'); // Customizable color
            $table->string('backgroundColor', 20)->default('#f1b44c'); // Customizable backgroundColor
            $table->string('dragBackgroundColor', 20)->default('#f1b44c'); // Customizable dragBackgroundColor
            $table->string('borderColor', 20)->default('#f1b44c'); // Customizable borderColor
            $table->boolean('is_editable')->default(false); // Allow rescheduling
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->unsignedBigInteger('schedule_id')->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->unsignedBigInteger('trainer_id')->index();
            $table->unsignedBigInteger('appointment_status')->default(7); //set default to pending

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('appointment_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('trainer_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
