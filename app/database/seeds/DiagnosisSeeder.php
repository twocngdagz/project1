<?php


class DiagnosisSeeder extends Seeder{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('diagnosis')->truncate();
        $reasons = new Diagnoses();
        $reasons->name = "Ankle";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Back";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Elbow";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Hip";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Knee";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Low back";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Neck";
        $reasons->company_id = null;
        $reasons->save();
        $reasons = new Diagnoses();
        $reasons->name = "Shoulder";
        $reasons->company_id = null;
        $reasons->save();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}