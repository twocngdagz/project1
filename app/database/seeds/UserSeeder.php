<?php


class UserSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        $faker = Faker\Factory::create();

        $user = array(
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'name' => 'John Doe',
            'position' => 'Admin all over this world',
            'practice_id' => 1,
            'role' => 'admin',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user1@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 2,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user2@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 3,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user3@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 4,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user4@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 5,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user5@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 6,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user6@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 7,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user7@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 8,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user8@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 9,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        $user = array(
            'email' => 'user9@yahoo.com',
            'password' => Hash::make('pass1234'),
            'name' => $faker->name,
            'position' => 'Tenant',
            'practice_id' => 10,
            'role' => 'manager',
            'confirmed' => true,
            'columns_patient' => '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"valueclm\":\"true\"}","is_array":true}',
            'filters_patient' => '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}',
        );

        DB::table('users')->insert($user);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}