<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolInitializationTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_creation_initializes_details_and_current_academic_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-27'));

        try {
            $school = School::factory()->create();

            $this->assertDatabaseHas('school_details', [
                'school_id' => $school->id,
                'meta_key' => 'school_logo',
                'meta_value' => '-',
            ]);

            $this->assertDatabaseHas('academic_years', [
                'school_id' => $school->id,
                'name' => '2025-2026',
                'status' => 0,
            ]);

            $this->assertDatabaseHas('academic_years', [
                'school_id' => $school->id,
                'name' => '2026-2027',
                'status' => 1,
            ]);

            $this->assertSame(2, AcademicYear::where('school_id', $school->id)->count());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_school_created_before_june_uses_previous_academic_start_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-03-15'));

        try {
            $school = School::factory()->create();

            $this->assertDatabaseHas('academic_years', [
                'school_id' => $school->id,
                'name' => '2024-2025',
                'status' => 0,
            ]);

            $this->assertDatabaseHas('academic_years', [
                'school_id' => $school->id,
                'name' => '2025-2026',
                'status' => 1,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }
}
