<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrasactionAccountTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (School::where('status', 1)->get() as $school) {
            $admin = User::where('school_id', $school->id)
                ->where('usergroup_id', 3)
                ->first();

            if (! $admin) {
                continue;
            }

            DB::table('transaction_accounts')->updateOrInsert(
                ['school_id' => $school->id, 'key' => 'CA'],
                [
                    'user_id' => $admin->id,
                    'account_number' => 'GSX-CASH-' . $school->id,
                    'ifsc_code' => 'CASH',
                    'name' => 'Cash Account',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
