<?php


class ActivitySeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activity')->truncate();
        $faker = Faker\Factory::create();


        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'Bay to Breakers Team 2015',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 1,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'Community Referral Event',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 2,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'Mission My Care',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 2,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'Mountain Valley Veterinary Hospital',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 2,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'January Galloway Run',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 3,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'March Galloway Run',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 3,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'June Galloway Run',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 3,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => $faker->name,
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 4,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => $faker->name,
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 4,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);

        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'November Newsletter',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 5,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'December Newsletter',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 5,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'January Newsletter',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 5,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'February Newsletter',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 5,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'Talks on Low Back Pain',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 6,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'January Community Event',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 7,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);
        Activities::create([
            'practice_id' => 1,
            'campaign_name' => 'Back Pain Month - Radio Ad',
            'conversions' => 0,
            'revenue' => 0,
            'cost' => $faker->numberBetween(1000,5000),
            'description' => $faker->sentence($nbWords = 6),
            'activity_type_id' => 7,
            'created_at' => $faker->dateTimeThisYear($max = '12/31.'.date("Y"))
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}