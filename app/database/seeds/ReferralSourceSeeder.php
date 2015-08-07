<?php


class ReferralSourceSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('referral_source')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,30) as $index)
        {
            ReferralSource::create([
                'practice_location_id' => $faker->numberBetween(1,30),
                'name' => 'Dr. ' . $faker->name,
                'referring_office_id' => $faker->numberBetween(1,20)
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}