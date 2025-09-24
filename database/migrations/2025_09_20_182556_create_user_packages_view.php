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
        \DB::statement("CREATE VIEW user_packages_view AS
            SELECT
                user_packages.id,
                user_packages.user_id,
                user_packages.package_id,
                user_packages.payment_id,
                user_packages.purchased_by,
                user_packages.deleted_by,
                user_packages.sessions_total,
                user_packages.sessions_used,
                user_packages.sessions_remaining,
                user_packages.validity_quantity,
                user_packages.validity_unit,
                CONCAT_WS(' ', user_packages.validity_quantity, user_packages.validity_unit) as validity,
                user_packages.purchased_at,
                user_packages.expires_at,
                user_packages.subscription_status,
                user_packages.notes,
                user_packages.created_at,
                user_packages.updated_at,
                user_packages.deleted_at,
                CONCAT_WS(' ', users.firstname, users.lastname) as member_name,
                packages.package,
                CONCAT_WS(' ', users_2.firstname, users_2.lastname) as purchased_by_name,
                CONCAT_WS(' ', users_3.firstname, users_3.lastname) as deleted_by_name,
                workflow_status.status_name as subscription_status_name,
                workflow_status.css as subscription_status_name_css
            FROM
                user_packages
                JOIN users ON user_packages.user_id = users.id
                JOIN packages ON user_packages.package_id = packages.id
                JOIN payments ON user_packages.payment_id = payments.id
                JOIN users users_2 ON user_packages.purchased_by = users_2.id
                LEFT JOIN users users_3 ON user_packages.deleted_by = users_3.id
                JOIN workflow_status ON user_packages.subscription_status = workflow_status.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW user_packages_view");
    }
};
