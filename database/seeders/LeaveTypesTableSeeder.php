<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Earned Leave or Privilege Leave', 'max_no_of_days' => 2],
            ['name' => 'Casual Leave', 'max_no_of_days' => 1],
            ['name' => 'Sick Leave or Medical Leave', 'max_no_of_days' => 1],
            ['name' => 'Maternity Leave', 'max_no_of_days' => 45],
            ['name' => 'Quarantine Leave', 'max_no_of_days' => 5],
            ['name' => 'Study Leave or Sabbatical Leave', 'max_no_of_days' => 7],
        ];

        foreach (School::where('status', 1)->get() as $school) {
            $academicYear = AcademicYear::where('school_id', $school->id)
                ->where('status', 1)
                ->first();

            if (! $academicYear) {
                continue;
            }

            foreach ($leaveTypes as $leaveType) {
                DB::table('leave_types')->updateOrInsert(
                    [
                        'school_id' => $school->id,
                        'academic_year_id' => $academicYear->id,
                        'name' => $leaveType['name'],
                    ],
                    [
                        'max_no_of_days' => $leaveType['max_no_of_days'],
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
