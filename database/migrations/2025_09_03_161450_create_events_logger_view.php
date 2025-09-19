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
        \DB::statement("CREATE VIEW events_logger_view AS
            SELECT
                events_logger.id,
                events_logger.user_id,
                events_logger.action,
                events_logger.module_section,
                events_logger.old_values,
                events_logger.new_values,
                events_logger.ip_address,
                events_logger.client_information,
                events_logger.created_by,
                events_logger.updated_by,
                events_logger.created_at,
                events_logger.updated_at,
                events_logger.event_status,
                CONCAT_WS(' ', users.firstname, users.lastname) as user_full_names,
                CONCAT_WS(' ', users_2.firstname, users_2.lastname) as created_by_name,
                CONCAT_WS(' ', users_3.firstname, users_3.lastname) as updated_by_name,
                workflow_status.status_name as event_status_name,
                workflow_status.css as event_status_name_css
            FROM
                events_logger
                LEFT JOIN users ON events_logger.user_id = users.id
                LEFT JOIN users users_2 ON events_logger.created_by = users_2.id
                LEFT JOIN users users_3 ON events_logger.updated_by = users_3.id
                JOIN workflow_status ON events_logger.event_status = workflow_status.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW events_logger_view");
    }
};
