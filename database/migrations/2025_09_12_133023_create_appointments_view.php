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
        \DB::statement("CREATE VIEW appointments_view AS
            SELECT
                appointments.id,
                appointments.title,
                appointments.appointment_image,
                appointments.description,
                appointments.start_date_time,
                appointments.end_date_time,
                appointments.slots,
                appointments.slots_taken,
                appointments.is_all_day,
                appointments.category,
                appointments.color,
                appointments.backgroundColor,
                appointments.dragBackgroundColor,
                appointments.borderColor,
                appointments.is_editable,
                appointments.created_at,
                appointments.updated_at,
                appointments.schedule_id,
                appointments.trainer_id,
                appointments.appointment_status,
                appointments.deleted_by,
                appointments.deleted_at,
                CONCAT_WS(' ', users.firstname, users.lastname) as trainer_name,
                CONCAT_WS(' ', users_2.firstname, users_2.lastname) as created_by_name,
                CONCAT_WS(' ', users_3.firstname, users_3.lastname) as updated_by_name,
                CONCAT_WS(' ', users_4.firstname, users_4.lastname) as deleted_by_name,
                schedules.estimated_time,
                schedules.location,
                schedules.location_latitude,
                schedules.location_longitude,
                schedules.schedule_image,
                schedules.recurring_status,
                schedules.schedule_status,
                workflow_status.status_name as appointment_status_name,
                workflow_status.css as appointment_status_name_css,
                workflow_status_2.status_name as schedule_status_name,
                workflow_status_2.css as schedule_status_name_css,
                workflow_status_3.status_name as recurring_status_name,
                workflow_status_3.css as recurring_status_name_css
            FROM
                appointments
                JOIN users ON appointments.trainer_id = users.id
                JOIN users users_2 ON appointments.updated_by = users_2.id
                JOIN users users_3 ON appointments.updated_by = users_3.id
                LEFT JOIN users users_4 ON appointments.deleted_by = users_4.id
                JOIN schedules ON appointments.schedule_id = schedules.id
                JOIN workflow_status ON appointments.appointment_status = workflow_status.id
                JOIN workflow_status workflow_status_2 ON schedules.schedule_status = workflow_status_2.id
                JOIN workflow_status workflow_status_3 ON schedules.recurring_status  = workflow_status_3.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         \DB::statement("DROP VIEW appointments_view");
    }
};
