<?php


class TaskSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('crm_tasks')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,1000) as $index)
        {
            Crmtasks::create([
                'title' => $faker->sentence($nbWords = 6),
                'owner_id' => $faker->numberBetween(1,10),
                'referring_office_id' => $faker->numberBetween(1,50),
                'assigned_to' => 1,
                'updater_id' => 1,
                'is_completed' => 0
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}