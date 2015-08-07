<?php


class ActivityTypeSeeder extends Seeder{

    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activity_type')->truncate();

        ActivityTypes::create([
            'name' => 'Community Event',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Community Referral Program',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Galloway Run',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Google Adwords',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Patient Newsletters',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Seminar',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Workshops',
            'practice_id' => 1
        ]);
        ActivityTypes::create([
            'name' => 'Radio',
            'practice_id' => 1
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}