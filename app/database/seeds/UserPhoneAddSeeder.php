<?php


class UserPhoneAddSeeder extends Seeder{

    public function run()
    {
        $users = User::all();
        foreach ($users as $user)
        {
            $user->columns_patient = '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"phoneclm\":\"false\"}","is_array":true}';
            $user->forceSave();
        }
    }
}