<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed shared reference data in every environment.
     *
     * Demo tenants, sample users and sample school records are created only
     * in local/development environments. Production schools are created by
     * the guarded school-registration workflow.
     */
    public function run(): void
    {
        // Shared application reference data required by a fresh installation.
        $this->call(UsergroupTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(PlansTableSeeder::class);
        $this->call(MailTemplatesSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(SmsTemplatesTableSeeder::class);
        $this->call(KeywordsTableSeeder::class);
        $this->call(QualificationTableSeeder::class);
        $this->call(AbsentReasonsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(BooksCategoryTableSeeder::class);
        $this->call(LeaveTypesTableSeeder::class);
        $this->call(PageCategoryTableSeeder::class);
        $this->call(PayCategoryTableSeeder::class);
        $this->call(PayrollItemTableSeeder::class);
        $this->call(TransactionTypeTableSeeder::class);
        $this->call(TrasactionAccountTableSeeder::class);
        $this->call(TagSeeder::class);

        // Never create demo school accounts or sample records in production.
        if (app()->environment(['local', 'development'])) {
            $this->call(SchoolsTableSeeder::class);
            $this->call(SchoolDetailsTableSeeder::class);
            $this->call(AcademicYearsTableSeeder::class);
            $this->call(SectionsTableSeeder::class);
            $this->call(StandardsTableSeeder::class);
            $this->call(SubjectsTableSeeder::class);

            $this->call(UsersSchoolAdminTableSeeder::class);
            $this->call(UsersTeacherTableSeeder::class);
            $this->call(UsersStudentTableSeeder::class);
            $this->call(TeacherTableSeeder::class);

            $this->call(StandardsLinkTableSeeder::class);
            $this->call(SubscriptionsTableSeeder::class);
            $this->call(BooksTableSeeder::class);
            $this->call(RoleUsersTableSeeder::class);
            $this->call(HolidaySeeder::class);
            $this->call(NoticeBoardSeeder::class);
            $this->call(PayrollSeeder::class);
        }
    }
}
