<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksCategoryTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Astro Physics', 'Arts', 'Comics', 'Computer Science', 'History',
            'Music', 'Technology', 'Magazines', 'Question Bank', 'Projects',
        ];

        foreach (School::where('status', 1)->get() as $school) {
            foreach ($categories as $category) {
                DB::table('books_category')->updateOrInsert(
                    ['school_id' => $school->id, 'category' => $category],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
