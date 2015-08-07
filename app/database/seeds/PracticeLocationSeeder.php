<?php


class PracticeLocationSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('practice_locations')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,30) as $index)
        {
            OfficeLocation::create([
                'practice_id' => 1,
                'name' => $faker->company,
                'phone' => $faker->phoneNumber,
                'fax' => $faker->phoneNumber,
                'website' => $faker->domainName,
                'address' => $faker->address,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}