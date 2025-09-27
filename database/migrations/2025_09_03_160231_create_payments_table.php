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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 50)->nullable($value = true); //payment gateway transaction id
            $table->string('payment_reference')->unique();
            $table->string('payment_gateway_currency', 5);
            $table->decimal('payment_amount', 20, 2)->default(0.00);
            $table->decimal('payment_processing_fee', 20, 2)->default(0.00); // 3% payment gateway fee
            $table->decimal('payment_tax', 20, 2)->default(0.00)->nullable($value = false); // payment gateway tax
            $table->string('payment_message')->nullable($value = true); //payment gateway response
            $table->string('balance_transaction')->nullable($value = true); //needed to later update balances
            $table->string('payment_charge_outcome')->nullable($value = true); //card brand if card payment
            $table->string('payment_method', 10)->nullable($value = true); //eg. card ,wallet
            $table->string('last4', 10)->nullable($value = true); //card last 4 digits
            $table->string('card_brand', 20)->nullable($value = true); //e.g VISA, Mastercard
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('payment_start_created'); // transaction datestamp from payment gateway
            $table->timestamp('payment_end_created'); // transaction datestamp from payment gateway
            $table->softDeletes();

            $table->unsignedBigInteger('package_id')->index();
            $table->unsignedBigInteger('payment_status')->default(7); //set default to pending
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->unsignedBigInteger('paid_by')->index();

            $table->foreign('payment_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('paid_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
