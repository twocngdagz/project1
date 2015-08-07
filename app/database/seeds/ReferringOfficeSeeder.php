<?php


class ReferringOfficeSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('referring_offices')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,50) as $index)
        {
            ReferringOffice::create([
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