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
        \DB::statement("CREATE VIEW users_view AS
        SELECT
            users.id,
            users.firstname,
            users.lastname,
            users.email,
            users.lockout_time,
            users.email_verified_at,
            users.created_at,
            users.updated_at,
            users.deleted_at,
            users.deleted_by,
            users.user_status,
            users.last_login_time,
            users.online_status,
            users.language_code,
            users.timezone,
            users.is_super_admin,
            users.is_trainer,
            users.is_client,
            users.profile_pic,
            languages.name,
            languages.native_name,
            workflow_status.status_name as user_status_name,
            workflow_status.css as user_status_name_css,
            workflow_status_2.status_name as online_status_name,
            workflow_status_2.css as online_status_name_css,
            workflow_status_3.status_name as is_super_admin_name,
            workflow_status_3.css as is_super_admin_name_css,
            workflow_status_4.status_name as is_trainer_name,
            workflow_status_4.css as is_trainer_name_css,
            workflow_status_5.status_name as is_client_name,
            workflow_status_5.css as is_client_name_css
        FROM
            users
            JOIN languages ON users.language_code = languages.code
            JOIN workflow_status ON users.user_status  = workflow_status.id
            JOIN workflow_status workflow_status_2 ON users.online_status = workflow_status_2.id
            JOIN workflow_status workflow_status_3 ON users.is_super_admin = workflow_status_3.id
            JOIN workflow_status workflow_status_4 ON users.is_trainer = workflow_status_4.id
            JOIN workflow_status workflow_status_5 ON users.is_client = workflow_status_5.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW users_view");
    }
};
