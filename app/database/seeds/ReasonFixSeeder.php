<?php

class ReasonFixSeeder  extends Seeder{

    public function run() {
        $practices = Practice::all();
        foreach ($practices as $practice)
        {
            $hours = new ReasonNotScheduled();
            $hours->description = "Hours don't fit schedule";
            $hours->practice_id = $practice->id;
            $hours->save();

            $location = new ReasonNotScheduled();
            $location->description = "Inconvenient location";
            $location->practice_id = $practice->id;
            $location->save();

            $insurance = new ReasonNotScheduled();
            $insurance->description = "Insurance";
            $insurance->practice_id = $practice->id;
            $insurance->save();



            $cases = $practice->cases;

            foreach ($cases as $case)
            {
                switch ($case->reasonnotscheduled_id)
                {
                    case 1:
                        $case->reasonnotscheduled_id = $hours->id;
                        break;
                    case 2:
                        $case->reasonnotscheduled_id = $location->id;
                        break;
                    case 3:
                        $case->reasonnotscheduled_id = $insurance->id;
                        break;
                }

                $case->save();
            }
        }
    }
} 