<?php

use Carbon\Carbon;

class CaseController extends BaseController {


    public function getCases()
    {
        if (Request::ajax())
        {
            $user = Auth::user();
            $practice = $user->practice;
            $referrals = $practice->referralSources;
            $activities=$practice->activities;
            $diagnoses = $practice->diagnosis();
            $activity_types= $practice->activityTypes;
            $patient = Patient::findOrFail(Input::get('patient_id'));
            $cases = $patient->cases;
            return View::make('site.patient.casetable', compact('referrals', 'activities', 'diagnoses', 'cases', 'activity_types'));
        }
    }


    public function checkinEvaluation()
    {
        if (Request::ajax())
        {
            $case = Cases::findOrFail(Input::get('case_id'));
            $case->free_evaluation = Carbon::now();
            $case->save();
            return $case->free_evaluation->format('m/d/Y');
        }
    }

    public function checkinAppointment()
    {
        if (Request::ajax())
        {
            $case = Cases::findOrFail(Input::get('case_id'));
            $case->first_appointment = Carbon::now();
            $case->save();
            return $case->first_appointment->format('m/d/Y');
        }
    }

    public function addCase()
    {
        if (Request::ajax())
        {
            $rules = Validator::make(
                array(
                    'referral'      =>  Input::get('referral_id'),
                    'diagnosis'     =>  Input::get('diagnosis_id'),
                    'activity'      =>  Input::get('activity_id'),
                    'scheduled'     =>  Input::get('scheduled')
                ),
                array(
                    'referral'      =>  'required',
                    'diagnosis'     =>  'required',
                    'activity'      =>  'required',
                    'scheduled'     =>  'required'
                ),
                array(
                    'referral.required' => 'Referral Source is required',
                    'diagnosis.required' => 'Diagnosis is required',
                    'activity.required' => 'How did you find us is required',
                    'scheduled' => 'Is Scheduled is required'
                )
            );

            if ($rules->fails())
            {
                return $rules->errors()->all();
            }
            $case = new Cases();
            $case->patient_id = Input::get('patient_id');
            $case->referralsource_id = Input::get('referral_id');
            $case->diagnosis_id = Input::get('diagnosis_id');
            $case->activity_id = Input::get('activity_id');
            $case->is_scheduled = Input::get('scheduled');
            $case->save();

            if ($case->id)
            {
                return 'success';
            } else {
                return 'error';
            }

        }
    }

    public function getCase($case_id)
    {
        $practice = Auth::user()->practice;
        $insurances = $practice->insurances;
        $therapists = $practice->therapists;
        $diagnosises = $practice->diagnosis();
        $referralsources = $practice->referralSources;
        $referralOffices = $practice->referringOffices;
        $reasons = $practice->reasons();
        $activity_types = $practice->activityTypes;
        $case = Cases::findOrFail($case_id);
        return View::make('site.case.edit', compact('case', 'insurances', 'therapists', 'referralsources', 'referralOffices', 'diagnosises', 'reasons', 'activity_types'));
    }

    public function updateCase()
    {
        $rules = Validator::make(
            array(
                'referral'      =>  Input::get('referral'),
                'diagnosis'     =>  Input::get('diagnosis'),
                'activity'      =>  Input::get('activity'),
                'scheduled'     =>  Input::get('is_scheduled')
            ),
            array(
                'referral'      =>  'required',
                'diagnosis'     =>  'required',
                'activity'      =>  'required',
                'scheduled'     =>  'required'
            ),
            array(
                'referral.required' => 'Referral Source is required',
                'diagnosis.required' => 'Diagnosis is required',
                'activity.required' => 'How did you find us is required',
                'scheduled' => 'Is Scheduled is required'
            )
        );

        if ($rules->fails())
        {
            return $rules->errors()->all();
        }
        $case = Cases::findOrFail(Input::get('case_id'));
        $case->insurance_id = Input::get('insurance') == 'notdefined' ? null : Input::get('insurance');
        $case->therapist_id = Input::get('therapist') == 'notdefined' ? null : Input::get('therapist');
        $case->referralsource_id = Input::get('referral') == 'notdefined' ? null : Input::get('referral');
        $case->diagnosis_id = Input::get('diagnosis');
        $case->reasonnotscheduled_id = Input::get('reason') == 'notdefined' ? null : Input::get('reason');
        $case->is_scheduled = Input::get('is_scheduled');
        $case->free_evaluation = Input::has('free_evaluation_date') ? new Carbon(Input::get('free_evaluation_date')) : null;
        $case->first_appointment = Input::has('first_appointment_date') ? new Carbon(Input::get('first_appointment_date')) : null;
        $case->activity_id = Input::get('activity');
        $case->save();
        return 'success';
    }
}