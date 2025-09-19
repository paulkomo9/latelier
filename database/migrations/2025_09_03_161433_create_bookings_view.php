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
        \DB::statement("CREATE VIEW bookings_view AS
            SELECT
                bookings.id,
                bookings.reference,
                bookings.user_id,
                bookings.appointment_id,
                bookings.booking_status,
                bookings.deleted_by,
                bookings.created_at,
                bookings.updated_at,
                bookings.attended_at,
                bookings.attendance_marked_by,
                appointments.start_date_time,
                appointments.end_date_time,
                appointments.trainer_id,
                schedules.title,
                schedules.description,
                schedules.estimated_time,
                schedules.slots,
                schedules.slots_taken,
                schedules.location,
                schedules.location_latitude,
                schedules.location_longitude,
                schedules.schedule_image,
                schedules.schedule_status,
                CONCAT_WS(' ', users.firstname, users.lastname) as booked_by_name,
                CONCAT_WS(' ', users_2.firstname, users_2.lastname) as deleted_by_name,
                CONCAT_WS(' ', users_3.firstname, users_3.lastname) as marked_by_name,
                CONCAT_WS(' ', users_4.firstname, users_4.lastname) as trainer_name,
                workflow_status.status_name as booking_status_name,
                workflow_status.css as booking_status_name_css,
                workflow_status_2.status_name as schedule_status_name,
                workflow_status_2.css as schedule_status_name_css
            FROM
                bookings
                JOIN users ON bookings.user_id = users.id
                LEFT JOIN users users_2 ON bookings.deleted_by = users_2.id
                LEFT JOIN users users_3 ON bookings.attendance_marked_by = users_3.id
                JOIN appointments ON bookings.appointment_id = appointments.id
                JOIN schedules ON appointments.schedule_id = schedules.id
                JOIN users users_4 ON appointments.trainer_id = users_4.id
                JOIN workflow_status ON bookings.booking_status = workflow_status.id
                JOIN workflow_status workflow_status_2 ON schedules.schedule_status = workflow_status_2.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW bookings_view");
    }
};
