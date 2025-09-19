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
        Schema::create('packages', function (Blueprint $table) {
           $table->id();
            $table->text('package'); 
            $table->text('package_image')->nullable();
            $table->unsignedInteger('sessions_total');
            $table->unsignedInteger('validity_quantity');
            $table->string('validity_unit');               // e.g. 'days', 'weeks', 'months'
            $table->text('description');
            $table->string('currency', 10);
            $table->decimal('amount', 20, 2)->default(0.00);
            $table->string('tax_type');               // e.g. 'fixed', 'percentage'
            $table->decimal('tax', 20, 2)->default(0.00);
            $table->decimal('total_amount', 20, 2)->default(0.00);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->unsignedBigInteger('package_status')->default(1);

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('package_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
