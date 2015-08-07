<?php


class DiagnosisFixSeeder extends Seeder{

    public function run()
    {
        $practices = Practice::all();
        foreach ($practices as $practice)
        {
            $foot_ankle = new Diagnoses();
            $foot_ankle->name = "Foot/Ankle";
            $foot_ankle->company_id = $practice->id;
            $foot_ankle->save();

            $balance = new Diagnoses();
            $balance->name = "Balance/Fall Prevention";
            $balance->company_id = $practice->id;
            $balance->save();

            $hip = new Diagnoses();
            $hip->name = "Hip";
            $hip->company_id = $practice->id;
            $hip->save();

            $knee = new Diagnoses();
            $knee->name = "Knee";
            $knee->company_id = $practice->id;
            $knee->save();

            $low_back = new Diagnoses();
            $low_back->name = "Low back/Sciatica";
            $low_back->company_id = $practice->id;
            $low_back->save();

            $neck = new Diagnoses();
            $neck->name = "Neck";
            $neck->company_id = $practice->id;
            $neck->save();

            $vestibular = new Diagnoses();
            $vestibular->name = "Vestibular/Dizziness";
            $vestibular->company_id = $practice->id;
            $vestibular->save();


            $shoulder = new Diagnoses();
            $shoulder->name = "Shoulder";
            $shoulder->company_id = $practice->id;
            $shoulder->save();

            $patients = $practice->patients;

            foreach ($patients as $patient)
            {
                switch ($patient->diagnosis_id)
                {
                    case 1:
                        $patient->diagnosis_id = $foot_ankle->id;
                        break;
                    case 2:
                        $patient->diagnosis_id = $balance->id;
                        break;
                    case 3:
                        $patient->diagnosis_id = $hip->id;
                        break;
                    case 4:
                        $patient->diagnosis_id = $knee->id;
                        break;
                    case 5:
                        $patient->diagnosis_id = $low_back->id;
                        break;
                    case 6:
                        $patient->diagnosis_id = $neck->id;
                        break;
                    case 7:
                        $patient->diagnosis_id = $vestibular->id;
                        break;
                    case 8:
                        $patient->diagnosis_id = $shoulder->id;
                        break;
                }
                $patient->save();
            }
        }


    }
}