<?php

namespace App\Observers;

use App\Models\SchoolDetail;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\School;
use Carbon\Carbon;
use Throwable;
use Log;

class SchoolObserver
{
    /**
     * Handle the school "created" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function created(School $school)
    {
        try {
            $school->slug = Str::slug($school->name, '-');
            $school->save();

            $keys = [
                'about_us', 'admission_open', 'admission_close_message',
                'admission_close_on', 'affiliation_no', 'affiliated_by',
                'board', 'date_of_establishment', 'landline_no', 'moto',
                'school_logo', 'website',
            ];

            foreach ($keys as $key) {
                SchoolDetail::firstOrCreate(
                    ['school_id' => $school->id, 'meta_key' => $key],
                    ['meta_value' => '-']
                );
            }

            if (AcademicYear::where('school_id', $school->id)->exists()) {
                return;
            }

            // Indian school academic years generally begin in June. Resolve
            // the current academic year from the current month, not calendar year.
            $now = Carbon::now();
            $startYear = $now->month >= 6 ? $now->year : $now->year - 1;
            $nextYear = $startYear + 1;
            $previousStartYear = $startYear - 1;

            AcademicYear::create([
                'school_id'   => $school->id,
                'name'        => $previousStartYear . '-' . $startYear,
                'description' => 'Previous Academic Year',
                'start_date'  => $previousStartYear . '-06-01',
                'end_date'    => $startYear . '-04-30',
                'status'      => 0,
            ]);

            AcademicYear::create([
                'school_id'   => $school->id,
                'name'        => $startYear . '-' . $nextYear,
                'description' => 'Current Academic Year',
                'start_date'  => $startYear . '-06-01',
                'end_date'    => $nextYear . '-04-30',
                'status'      => 1,
            ]);
        } catch (Throwable $e) {
            Log::error('GSX school initialization failed', [
                'school_id' => $school->id ?? null,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle the school "updated" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function updated(School $school)
    {
        //
    }

    /**
     * Handle the school "deleted" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function deleted(School $school)
    {
        //
    }

    /**
     * Handle the school "restored" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function restored(School $school)
    {
        //
    }

    /**
     * Handle the school "force deleted" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function forceDeleted(School $school)
    {
        //
    }
}