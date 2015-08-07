<?php


class NoteSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('crm_notes')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,1000) as $index)
        {
            Crmnotes::create([
                'description' => $faker->paragraph($nbSentences = 3),
                'owner_id' => 1,
                'referring_office_id' => $faker->numberBetween(1,50)
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}