<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class WorkflowStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'status_name' => 'ACTIVE', 'css' => 'badge bg-success'],
            ['id' => 2, 'status_name' => 'DEACTIVATED', 'css' => 'badge bg-danger'],
            ['id' => 3, 'status_name' => 'ONLINE', 'css' => 'badge bg-success'],
            ['id' => 4, 'status_name' => 'OFFLINE', 'css' => 'badge bg-dark'],
            ['id' => 5, 'status_name' => 'YES', 'css' => 'badge bg-success'],
            ['id' => 6, 'status_name' => 'NO', 'css' => 'badge bg-warning'],
            ['id' => 7, 'status_name' => 'PENDING', 'css' => 'badge bg-warning'],
            ['id' => 8, 'status_name' => 'COMPLETED', 'css' => 'badge bg-success'],
            ['id' => 9, 'status_name' => 'APPROVED', 'css' => 'badge bg-success'],
            ['id' => 10, 'status_name' => 'REJECTED', 'css' => 'badge bg-danger'],
            ['id' => 11, 'status_name' => 'DISPUTED', 'css' => 'badge bg-warning'],
            ['id' => 12, 'status_name' => 'CANCELLED', 'css' => 'badge bg-dark'],
            ['id' => 13, 'status_name' => 'SUCCEEDED', 'css' => 'badge bg-success'],
            ['id' => 14, 'status_name' => 'REFUNDED', 'css' => 'badge bg-primary'],
            ['id' => 15, 'status_name' => 'CONFIRMED', 'css' => 'badge bg-success'],
            ['id' => 16, 'status_name' => 'RESCHEDULED', 'css' => 'badge bg-warning'],
            ['id' => 17, 'status_name' => 'FAILED', 'css' => 'badge bg-danger'],
            ['id' => 18, 'status_name' => 'AWAITING APPROVAL', 'css' => 'badge bg-warning'],
            ['id' => 19, 'status_name' => 'VERIFIED', 'css' => 'badge bg-success'],
            ['id' => 20, 'status_name' => 'SOLD OUT', 'css' => 'badge bg-danger'],
            ['id' => 21, 'status_name' => 'VALID', 'css' => 'badge bg-success'],
            ['id' => 22, 'status_name' => 'EXPIRED', 'css' => 'badge bg-danger'],
        ];

        DB::table('workflow_status')->insert($statuses);
    }
}
