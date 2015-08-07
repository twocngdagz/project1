<?php


class InsuranceSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('insurance')->truncate();

        Insurance::create([
            'name' => 'Anthem Blue Cross',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Blue Shield of California',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Chinese Community Health Plan',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Western Health Advantage',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'AlwaysCare',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Assurant Health',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Delta Dental Insurance Company (Delta Dental)',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Delta Dental of California',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'Nationwide Life Insurance Company',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'UnitedHealthOne',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'HCC Life Insurance Company',
            'practice_id' => 1,
        ]);
        Insurance::create([
            'name' => 'IHC Group',
            'practice_id' => 1,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}