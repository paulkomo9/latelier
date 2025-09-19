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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('subject');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->unsignedBigInteger('sent_by')->index();
            $table->unsignedBigInteger('recipient_id')->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();

            $table->foreign('sent_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
