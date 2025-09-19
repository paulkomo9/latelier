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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->text('title'); // For TUI Calendar title
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->text('description');
            $table->text('schedule_image')->nullable();
            $table->unsignedBigInteger('estimated_time')->default(0);
            $table->unsignedSmallInteger('slots')->default(0);
            $table->unsignedSmallInteger('slots_taken')->default(0);
            $table->text('location');
            $table->decimal('location_latitude', 11, 8);
            $table->decimal('location_longitude', 11, 8);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->unsignedBigInteger('trainer_id')->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->unsignedBigInteger('schedule_status')->default(1);
            $table->unsignedBigInteger('recurring_status')->default(6);

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('trainer_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('schedule_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('recurring_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
