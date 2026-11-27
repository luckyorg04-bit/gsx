<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SchoolProvisioningService
{
    /**
     * Create the minimum school-scoped reference records needed after
     * registration. Safe to call more than once for the same school.
     */
    public function seedDefaults(School $school, User $admin): void
    {
        $academicYear = AcademicYear::where('school_id', $school->id)
            ->where('status', 1)
            ->first();

        if (! $academicYear) {
            throw new RuntimeException('A current academic year is required before provisioning school defaults.');
        }

        $now = now();

        $bookCategories = [
            'Astro Physics', 'Arts', 'Comics', 'Computer Science', 'History',
            'Music', 'Technology', 'Magazines', 'Question Bank', 'Projects',
        ];

        foreach ($bookCategories as $category) {
            DB::table('books_category')->updateOrInsert(
                ['school_id' => $school->id, 'category' => $category],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        $leaveTypes = [
            ['name' => 'Earned Leave or Privilege Leave', 'max_no_of_days' => 2],
            ['name' => 'Casual Leave', 'max_no_of_days' => 1],
            ['name' => 'Sick Leave or Medical Leave', 'max_no_of_days' => 1],
            ['name' => 'Maternity Leave', 'max_no_of_days' => 45],
            ['name' => 'Quarantine Leave', 'max_no_of_days' => 5],
            ['name' => 'Study Leave or Sabbatical Leave', 'max_no_of_days' => 7],
        ];

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
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $pageCategories = [
            'english', 'general_knowledge', 'mathematics', 'others',
            'science', 'social_studies', 'tamil',
        ];

        foreach ($pageCategories as $category) {
            DB::table('class_room_page_categories')->updateOrInsert(
                [
                    'school_id' => $school->id,
                    'academic_year_id' => $academicYear->id,
                    'name' => $category,
                ],
                [
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        DB::table('transaction_accounts')->updateOrInsert(
            ['school_id' => $school->id, 'key' => 'CA'],
            [
                'user_id' => $admin->id,
                'account_number' => 'GSX-CASH-' . $school->id,
                'ifsc_code' => 'CASH',
                'name' => 'Cash Account',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
