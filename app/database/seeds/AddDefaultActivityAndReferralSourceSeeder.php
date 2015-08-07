<?php

class AddDefaultActivityAndReferralSourceSeeder  extends Seeder {

	public function run()
	{
		$practices = Practice::all();

		foreach ($practices as $practice)
		{
			$referring_office = ReferringOffice::firstOrCreate(array('name' => 'Direct Access', 'practice_id'=> $practice->id));
            $referral_source = ReferralSource::firstOrCreate(array('name' => 'Direct Access', 'referring_office_id' => $referring_office->id));
            $act_type = ActivityTypes::firstOrCreate(array('name' => 'Activity Type', 'practice_id'=> $practice->id));
            $act = Activities::firstOrCreate(array('campaign_name' => 'Referral Source', 'practice_id'=> $practice->id, 'activity_type_id'=> $act_type->id));
		}
	}
	
}