<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW payments_view AS
            SELECT
                payments.id,
                payments.transaction_id,
                payments.payment_reference,
                payments.payment_gateway_currency,
                payments.payment_amount,
                payments.payment_processing_fee,
                payments.payment_tax,
                payments.payment_message,
                payments.balance_transaction,
                payments.payment_charge_outcome,
                payments.payment_method,
                payments.last4,
                payments.card_brand,
                payments.payment_start_created,
                payments.payment_end_created,
                payments.created_at,
                payments.updated_at,
                payments.deleted_at,
                payments.package_id,
                payments.payment_status,
                payments.deleted_by,
                payments.paid_by,
                packages.package,
                packages.amount,

                -- Deleted by user full name
                CONCAT_WS(' ', deleter.firstname, deleter.lastname) AS deleted_by_name,

                -- Workflow status for payment
                workflow_status.status_name AS payment_status_name,
                workflow_status.css AS payment_status_name_css

            FROM payments

            -- Package-related join
            JOIN packages ON payments.package_id = packages.id

            -- Deleted by user
            LEFT JOIN users AS deleter ON payments.deleted_by = deleter.id

             -- Paid by user
            JOIN users AS payer ON payments.paid_by = payer.id

            -- Workflow status
            JOIN workflow_status ON payments.payment_status = workflow_status.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS payments_view");
    }
};
