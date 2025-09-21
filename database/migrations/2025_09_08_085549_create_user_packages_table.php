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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();

            // Ownership & Source
            $table->unsignedBigInteger('user_id')->index();             // who owns the package
            $table->unsignedBigInteger('package_id')->index();          // which package definition
            $table->unsignedBigInteger('payment_id')->nullable();       // optional link to payment
            $table->unsignedBigInteger('purchased_by')->nullable();     // admin/staff who purchased
            $table->unsignedBigInteger('deleted_by')->nullable()->index();

            // Usage tracking
            $table->unsignedInteger('sessions_total');                  // e.g. 10
            $table->unsignedInteger('sessions_used')->default(0);
            $table->unsignedInteger('sessions_remaining')->default(0);  // could be derived, or updated

            // Validity
            $table->unsignedInteger('validity_quantity');               // e.g. 2
            $table->string('validity_unit');                            // e.g. weeks
            $table->dateTime('purchased_at');
            $table->dateTime('expires_at');                             // actual expiry date

            // Status
            $table->unsignedBigInteger('subscription_status')->default(1);           // link to workflow_status
            $table->text('notes')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreign('purchased_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('subscription_status')->references('id')->on('workflow_status')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_packages');
    }
};
