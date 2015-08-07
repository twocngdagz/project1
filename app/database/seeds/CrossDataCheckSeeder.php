<?php

Class CrossDataCheckSeeder extends Seeder{

	public function run()
	{
		$patients = Patient::all();
		foreach ($patients as $patient) {
			foreach ($patient->cases as $case)
			{
				if (!$this->isDiagnosisBelongsToPractice($case->diagnosis_id, $patient->practice->id))
				{
					$case->diagnosis_id = null;
				}
				if (!$this->isInsuranceBelongsToPractice($case->insurance_id, $patient->practice->id))
				{
					$case->insurance_id = null;
				}
				if (!$this->isReferralSourceBelongsToPractice($case->referralsource_id, $patient->practice->id))
				{
					$case->referralsource_id = null;
				}
				if (!$this->isActivityBelongsToPractice($case->activity_id, $patient->practice->id))
				{
					$case->activity_id = null;
				}
				if (!$this->isReasonNotScheduledBelongsToPractice($case->reasonnotscheduled_id, $patient->practice->id))
				{
					$case->reasonnotscheduled_id = null;
				}
				if ($this->isTherapistBelongsToPractice($case->therapist_id, $patient->practice->id))
				{
					$case->therapist_id = null;
				}
				$case->save();
			}
		}

	}

	public function findPractice($practice_id)
	{
		return Practice::findOrFail($practice_id);
	}

	public function isDiagnosisBelongsToPractice($diagnosis_id, $practice_id)
	{
		$practice = $this->findPractice($practice_id);
		foreach ($practice->diagnosis() as $diagnosis)
		{
			if ($diagnosis_id == $diagnosis->id)
			{
				return true;
			}
		}
		return false;
	}

	public function isInsuranceBelongsToPractice($insurance_id, $practice_id)
	{
		$practice = $this->findPractice($practice_id);
		foreach ($practice->insurances as $insurance) {
			if ($insurance_id == $insurance->id)
			{
				return true;
			}
		}
		return false;
	}

	public function isReferralSourceBelongsToPractice($referralsource_id, $practice_id)
	{
		$practice = $this->findPractice($practice_id);
		foreach ($practice->referralSources as $referralSources) {
			if ($referralsource_id == $referralSources->id)
			{
				return true;
			}
		}
		return false;
	}

	public function isActivityBelongsToPractice($activity_id, $practice_id)
	{
		$practice = $this->findPractice($practice_id);
		foreach ($practice->activities as $activity) {
			if ($activity_id == $activity->id)
			{
				return true;
			}
		}
		return false;
	}

	public function isReasonNotScheduledBelongsToPractice($reasonnotscheduled_id, $practice_id)
	{
		$practice = $this->findPractice($practice_id);
		foreach ($practice->reasons() as $reason)
		{
			if ($reasonnotscheduled_id == $reason->id)
			{
				return true;
			}
		}
		return false;
	}

	public function isTherapistBelongsToPractice($therapist_id, $practice_id)
	{
		$practice = $this->findPractice($practice_id);
		foreach ($practice->therapists as $therapist) 
		{
			if ($therapist_id == $therapist->id)
			{
				return true;
			}
		}
		return false;	
	}

}