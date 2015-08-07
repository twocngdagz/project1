<?php


class CaseAppointmentScheduledFix extends Seeder{

    public function run()
    {
        $cases = Cases::all();
        foreach ($cases as $case)
        {
            if ($this->IsNotNullAndNotEmpty($case->free_evaluation) || $this->IsNotNullAndNotEmpty($case->first_appointment))
            {
                $case->is_scheduled = true;
                $case->reasonnotscheduled_id = null;
                $case->save();
            }
        }
    }

    private function IsNotNullAndNotEmpty($question){
        return (!(!isset($question) || trim($question)===''));
    }

}
