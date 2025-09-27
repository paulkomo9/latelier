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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email')->unique();
            $table->string('profile_pic', 200)->nullable();
            $table->integer('lockout_time')->default(30);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('api_token', 60)->unique()->nullable();
            $table->softDeletes();

            $table->unsignedBigInteger('user_status')->default(1);
            $table->unsignedBigInteger('online_status')->default(4);
            $table->unsignedBigInteger('is_client')->default(5);
            $table->unsignedBigInteger('is_trainer')->default(6);
            $table->unsignedBigInteger('is_super_admin')->default(6);
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->string('language_code', 3)->index()->default('en');
            

            $table->timestamp('last_login_time')->nullable();
            $table->string('timezone')->default("Asia/Dubai");

            $table->foreign('user_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('online_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('is_client')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('is_trainer')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('is_super_admin')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('language_code')->references('code')->on('languages')->onDelete('restrict')->onUpdate('cascade');
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
