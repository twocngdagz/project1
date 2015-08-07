<?php


class PracticeSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('practice')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,1) as $index)
        {
            Practice::create([
                'name' => $faker->company,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}