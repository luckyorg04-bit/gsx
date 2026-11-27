<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageCategoryTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'english', 'general_knowledge', 'mathematics', 'others',
            'science', 'social_studies', 'tamil',
        ];

        foreach (School::where('status', 1)->get() as $school) {
            $academicYear = AcademicYear::where('school_id', $school->id)
                ->where('status', 1)
                ->first();

            if (! $academicYear) {
                continue;
            }

            foreach ($categories as $category) {
                DB::table('class_room_page_categories')->updateOrInsert(
                    [
                        'school_id' => $school->id,
                        'academic_year_id' => $academicYear->id,
                        'name' => $category,
                    ],
                    ['status' => 1, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
