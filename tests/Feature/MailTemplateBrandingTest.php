<?php

namespace Tests\\Feature;

use Database\\Seeders\\MailTemplatesSeeder;
use Illuminate\\Foundation\\Testing\\RefreshDatabase;
use Illuminate\\Support\\Facades\\DB;
use Tests\\TestCase;

class MailTemplateBrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_mail_templates_are_gsx_branded_and_safe_to_seed_repeatedly(): void
    {
        $seeder = app(MailTemplatesSeeder::class);

        $seeder->run();
        $seeder->run();

        $this->assertSame(15, DB::table('mailtemplates')->count());
        $this->assertSame(
            0,
            DB::table('mailtemplates')
                ->where('mail_content', 'like', '%Administration Team%')
                ->count()
        );
        $this->assertSame(
            0,
            DB::table('mailtemplates')
                ->where('mail_content', 'not like', '%GSX Support Team%')
                ->count()
        );
    }
}
