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
        \DB::statement("CREATE VIEW schedules_view AS
            SELECT
                schedules.id,
                schedules.title,
                schedules.schedule_image,
                schedules.start_date_time,
                schedules.end_date_time,
                schedules.description,
                schedules.estimated_time,
                schedules.slots,
                schedules.slots_taken,
                schedules.location,
                schedules.location_latitude,
                schedules.location_longitude,
                schedules.recurring_status,
                schedules.created_at,
                schedules.updated_at,
                schedules.deleted_at,
                schedules.created_by,
                schedules.updated_by,
                schedules.trainer_id,
                schedules.deleted_by,
                schedules.schedule_status,
                CONCAT_WS(' ', users.firstname, users.lastname) as created_by_name,
                CONCAT_WS(' ', users_2.firstname, users_2.lastname) as updated_by_name,
                CONCAT_WS(' ', users_3.firstname, users_3.lastname) as deleted_by_name,
                CONCAT_WS(' ', users_4.firstname, users_4.lastname) as trainer_name,
                workflow_status.status_name as schedule_status_name,
                workflow_status.css as schedule_status_name_css,
                workflow_status_2.status_name as recurring_status_name,
                workflow_status_2.css as recurring_status_name_css
            FROM
                schedules
                JOIN users ON schedules.created_by = users.id
                JOIN users users_2 ON schedules.updated_by = users_2.id
                LEFT JOIN users users_3 ON schedules.deleted_by = users_3.id
                JOIN users users_4 ON schedules.trainer_id = users_4.id
                JOIN workflow_status ON schedules.schedule_status  = workflow_status.id
                JOIN workflow_status workflow_status_2 ON schedules.recurring_status  = workflow_status_2.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW schedules_view");
    }
};
