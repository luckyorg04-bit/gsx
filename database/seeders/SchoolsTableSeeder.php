<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Create a local-only demo school. SchoolObserver initializes its details
     * and current/previous academic years.
     */
    public function run(): void
    {
        School::create([
            'name'   => env('GSX_DEMO_SCHOOL_NAME', 'GSX Demo School'),
            'email'  => env('GSX_DEMO_SCHOOL_EMAIL', 'demo@gsx.local'),
            'phone'  => env('GSX_DEMO_SCHOOL_PHONE', '0000000000'),
            'status' => 1,
        ]);
    }
}
