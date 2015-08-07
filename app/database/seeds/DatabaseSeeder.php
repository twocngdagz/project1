<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->call('PracticeSeeder');
        $this->command->info('Practice table seeded!');
        $this->call('ActivityTypeSeeder');
        $this->command->info('ActivityType table seeded!');
        $this->call('ActivitySeeder');
        $this->command->info('Activity table seeded!');
        $this->call('DiagnosisSeeder');
        $this->command->info('Diagnosis table seeded!');
        $this->call('InsuranceSeeder');
        $this->command->info('Insurance table seeded!');
        $this->call('NoteSeeder');
        $this->command->info('Note table seeded!');
        $this->call('PracticeLocationSeeder');
        $this->command->info('Practice Location table seeded!');
        $this->call('ReasonNotScheduledSeeder');
        $this->command->info('Reason table seeded!');
        $this->call('ReferringOfficeSeeder');
        $this->command->info('Referring Office table seeded!');
        $this->call('ReferralSourceSeeder');
        $this->command->info('Referral Source table seeded!');
        $this->call('TaskSeeder');
        $this->command->info('Task table seeded!');
        $this->call('TherapistSeeder');
        $this->command->info('Therapist table seeded!');
        $this->call('UserSeeder');
        $this->command->info('User table seeded!');
        $this->call('PatientSeeder');
        $this->command->info('Patient table seeded!');

    }

}