<?php

use Carbon\Carbon;

class TransferDataFromPatientToCasesSeeder extends Seeder{

    public function run()
    {
        $patients = Patient::all();
        foreach ($patients as $patient)
        {
            Cases::create([
                'patient_id' => $patient->id,
                'diagnosis_id' => $patient->diagnosis_id,
                'referralsource_id' => $patient->referral_source_id,
                'activity_id' => $patient->activity_id,
                'first_appointment' => $patient->first_appointment ? new Carbon($patient->first_appointment) : NULL,
                'is_scheduled' => $patient->is_scheduled,
                'reasonnotscheduled_id' => $patient->reasonnotscheduled_id,
                'therapist_id' => $patient->therapist_id,
                'insurance_id' => $patient->insurance_id,
                'created_at' => $patient->created_at ? new Carbon($patient->created_at) : NULL
            ]);
        }
    }

}