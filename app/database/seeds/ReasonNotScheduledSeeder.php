<?php

class ReasonNotScheduledSeeder extends Seeder {

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('reasons')->truncate();
        $reasons = new ReasonNotScheduled();
        $reasons->description = "Hours don't fit schedule";
        $reasons->save();
        $reasons = new ReasonNotScheduled();
        $reasons->description = "Inconvenient location";
        $reasons->save();
        $reasons = new ReasonNotScheduled();
        $reasons->description = "Insurance";
        $reasons->save();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}