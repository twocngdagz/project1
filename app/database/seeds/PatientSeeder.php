<?php


class PatientSeeder extends Seeder{

    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('patient')->truncate();
        $faker = Faker\Factory::create();

        foreach(range(1,250) as $index)
        {
            Patient::create([
                'name'                => $faker->name,
                'phone'               => $faker->phoneNumber,
                'referral_source_id'  => $faker->numberBetween(1,30),
                'activity_id'         => $faker->numberBetween(1,16),
                'how_found_id'        => '0',
                'is_activities_type'  => '0',
                'is_scheduled'        => rand(0,1),
                'practice_id'         => 1,
                'insurance_id'        => $faker->numberBetween(1,12),
                'diagnosis_id'		  => $faker->numberBetween(1,8),
                'value'               => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
                'diag_desc'           => $faker->paragraph($nbSentences = 4),
				'address1'            => $faker->address,
                'address2'            => $faker->address,
                'reason_no_schedule'  => '',
                'notes'				  => $faker->paragraph($nbSentences = 3),
                'date_of_birth'  	  => $faker->dateTimeThisCentury($max = 'now'),
                'reasonnotscheduled_id' => $faker->numberBetween(1,3),
                'practice_location_id' => $faker->numberBetween(1,30),
                'first_appointment'     => $faker->dateTimeThisYear($max = '12/31.'.date("Y")),
                'created_at'            => $faker->dateTimeThisYear($max = '12/31.'.date("Y")),
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}