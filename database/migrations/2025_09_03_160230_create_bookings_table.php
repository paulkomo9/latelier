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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->text('reference');

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('appointment_id')->index();
            $table->unsignedBigInteger('booking_status')->default(1);
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->unsignedBigInteger('attendance_marked_by')->nullable()->index();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('attended_at')->nullable();
            $table->softDeletes();


            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('booking_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('attendance_marked_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
