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
        \DB::statement("CREATE VIEW packages_view AS
            SELECT
                packages.id,
                packages.package,
                packages.package_image,
                packages.sessions_total,
                packages.validity_quantity,
                packages.validity_unit,
                CONCAT_WS(' ', packages.validity_quantity,  packages.validity_unit) as validity,
                packages.description,
                packages.currency,
                packages.amount,
                packages.tax_type,
                packages.tax,
                packages.total_amount,
                packages.created_at,
                packages.updated_at,
                packages.deleted_at,
                packages.created_by,
                packages.updated_by,
                packages.deleted_by,
                packages.package_status,
                CONCAT_WS(' ', users.firstname, users.lastname) as created_by_name,
                CONCAT_WS(' ', users_2.firstname, users_2.lastname) as updated_by_name,
                CONCAT_WS(' ', users_3.firstname, users_3.lastname) as deleted_by_name,
                workflow_status.status_name as package_status_name,
                workflow_status.css as package_status_name_css
            FROM
                packages
                JOIN users ON packages.created_by = users.id
                JOIN users users_2 ON packages.updated_by = users_2.id
                LEFT JOIN users users_3 ON packages.deleted_by = users_3.id
                JOIN workflow_status ON packages.package_status  = workflow_status.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW packages_view");
    }
};
