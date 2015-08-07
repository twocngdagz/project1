<?php


class TherapistSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('therapists')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,100) as $index)
        {
            Therapists::create([
                'practice_id' => $faker->numberBetween(1,10),
                'name' => $faker->company,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}