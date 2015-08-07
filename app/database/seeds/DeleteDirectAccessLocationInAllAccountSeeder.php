<?php


class DeleteDirectAccessLocationInAllAccountSeeder extends Seeder{

    public function run()
    {
        $practice_locations = OfficeLocation::whereName('Direct Access')->get();
        foreach ($practice_locations as $location)
        {
            $referral_sources = $location->referralSource;
            foreach($referral_sources as $referral_source)
            {
                $referral_source->practice_location_id = NULL;
                $referral_source->save();
            }

            $patients = $location->patients;
            foreach ($patients as $patient)
            {
                $patient->practice_location_id = NULL;
                $patient->save();
            }


            $location->delete();
        }
    }

}