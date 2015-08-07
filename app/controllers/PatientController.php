<?php


class PatientController extends BaseController {

    protected $patient;
    public function __construct(Patient $patient)
    {
        parent::__construct();
        $this->patient = $patient;
    }    

	public function getPatientpage(){
		if(Auth::user()) {
			$user = Auth::user();
            $practice = $user->practice;
            $referralSources = $practice->referralSources;
			$activities=$practice->activities;
            $activityTypes= $practice->activityTypes;
            $reasons = $practice->reasons();
            $diagnoses = $practice->diagnosis();
            $location = $practice->officeLocations;
            return View::make('site/patient/patient')->with('referralsource', $referralSources)->with('all_activities', $activities)
                ->with('arr_activitytypes', $activityTypes)->with('arr_diagnoses', $diagnoses)->with('reasons', $reasons)->with('location', $location);
		}
	}


    //postPatientsList
    // @return template with data
    public function anyPatientslist()
    {
         if (Request::ajax())
        {
                $user = Auth::user();
                // filters in use
                $jsonfilters = json_decode($user->filters_patient);
                $filters = json_decode($jsonfilters->data, $jsonfilters->is_array);
                $allpatsforuser_notpaginated = $user->practice->patients()->orderBy('patient.created_at', 'desc')
                    ->join('cases', function($join)
                    {
                        $join->on('patient.id', '=', 'cases.patient_id');

                    });

                if(in_array('scheduledno',$filters)) {
                    $allpatsforuser_notpaginated->where('cases.is_scheduled', '=', 0);
                }
                if(in_array('scheduledyes',$filters)){
                    $allpatsforuser_notpaginated->where('cases.is_scheduled', '=', 1);

                }
                if(in_array('showedupno',$filters)) {
                    $allpatsforuser_notpaginated->whereNull('cases.first_appointment');
                }
                if(in_array('showedupyes',$filters)){
                    $allpatsforuser_notpaginated->whereNotNull('cases.first_appointment');
                }

				$array_diagnosis=array();
				foreach($filters as $filter){
					if(!is_numeric($filter)) {
						$diagnosis=Diagnoses::where('name', '=', $filter)->where('company_id','=', $user->practice->id)->first();
						if($diagnosis) {
							$array_diagnosis[]=$diagnosis->id;
						}
					}
				}
				if(!empty($array_diagnosis)){
					$allpatsforuser_notpaginated->whereIn('diagnosis_id', $array_diagnosis);
				}
				$array_doctor=array();
				foreach($filters as $filter){
					if(is_numeric($filter)) {
						$array_doctor[]=$filter;
					}
				}
				if(!empty($array_doctor)){
					$allpatsforuser_notpaginated->whereIn('referralsource_id', $array_doctor);
				}

                $allpatsforuser = $allpatsforuser_notpaginated->paginate(100,
                    array(
                        'patient.id',
                        'patient.practice_location_id',
                        'patient.name',
                        'patient.phone',
                        'cases.diagnosis_id',
                        'cases.referralsource_id',
                        'cases.activity_id',
                        'cases.reasonnotscheduled_id',
                        'cases.insurance_id',
                        'cases.therapist_id',
                        'cases.first_appointment',
                        'cases.free_evaluation',
                        'cases.is_scheduled',
                        'cases.created_at'
                    )
                );
                //~ columns filtering
                $firstobjjson = json_decode ($user->columns_patient);
                $columns = json_decode($firstobjjson->data, $firstobjjson->is_array);
                $massmessage = array(
                    'patientclm' => 'Patient',
                    'dateinitiatedclm' => 'Date initiated',
                    'findusclm' => 'How did you find us?',
                    'insuranceclm' => 'Insurance',
                    'isscheduled' => 'Scheduled?',
					'showedupclm' => 'Showed Up?',
                    'reasonclm' => 'Reason Not Scheduled',
                    'referralclm' => 'Referral Source',
					'diagnosisclm' => 'Diagnosis',
                    'clinicclm' => 'Location',
                    'phoneclm' => 'Phone Number'
                );

                return View::make('site/patient/patienttable')->with('patients', $allpatsforuser)->with('columns', $columns)->with('columnsmessages', $massmessage);
        } else
        {
            return false;
        }
    }

    //postColumnsList
    // @return template with data
    public function postColumnslist()
    {
        if (Request::ajax())
        {
            if (Input::get('getcolumns') == '1')
            {// deserialize
                $thisuser = User::find(Auth::id());
                $firstobjjson = json_decode ($thisuser->columns_patient);
                $columns = json_decode($firstobjjson->data, $firstobjjson->is_array);
                unset($columns['valueclm']);
                $massmessage = array(
                    'patientclm' => 'Patient',
                    'dateinitiatedclm' => 'Date initiated',
                    'findusclm' => 'How did you find us?',
                    'insuranceclm' => 'Insurance',
                    'isscheduled' => 'Scheduled?',
					'showedupclm' => 'Showed Up?',
                    'reasonclm' => 'Reason Not Scheduled',
                    'referralclm' => 'Referral Source',
					'diagnosisclm' => 'Diagnosis',
                    'clinicclm' => 'Location',
                    'phoneclm' => 'Phone Number'
                );
                return View::make('site/patient/columns')->with('columns', $columns)->with('columnsmessages', $massmessage);
                
            } else
            {
                return false;
            }
        } else
        {
            return false;
        }   
    }
    
    //~ gathering and saving columns state n patientscreen
    public function postSavecolumns()
    {
        if (Request::ajax())
        {
            $datatoblob = array(
                'patientclm' => Input::get('patientclm'),
                'dateinitiatedclm' => Input::get('dateinitiatedclm'),
                'findusclm' => Input::get('findusclm'),
                'insuranceclm' => Input::get('insuranceclm'),
                'isscheduled' => Input::get('isscheduled'),
				'showedupclm' => Input::get('showedupclm'),
                'reasonclm' => Input::get('reasonclm'),
                'referralclm' => Input::get('referralclm'),
				'diagnosisclm' => Input::get('diagnosisclm'),
                'clinicclm' => Input::get('clinicclm'),
                'phoneclm' => Input::get('phoneclm'),
            );

            
            $checkuserid = Auth::id();
            $autheduser = User::find(Auth::id());
            $objforuser = new stdClass;
            $objforuser->data = json_encode($datatoblob);
            $objforuser->is_array = is_array($datatoblob); // doing this for proper deserialize
            $objforuser_json = json_encode($objforuser);
            $autheduser->columns_patient = json_encode($objforuser);
            if ($autheduser->forceSave())
            {
                return 'success';
            } 
        } else
        {
            return 'nope';
        }
    }

    public function postAddpatient()
    {
        if (Request::ajax())
        {
            $newpatient = new Patient;
            $case = new Cases;
            $case->diagnosis_id = Input::get('inputdiagnosis') ? Input::get('inputdiagnosis') : null;
            $case->reasonnotscheduled_id = Input::get('inputreasonnotscheduled') ? Input::get('inputreasonnotscheduled') : null;
            $newpatient->name = Input::get('patientname');
            $newpatient->phone = Input::get('patientphone');
            $newpatient->practice_location_id = Input::get('location') ? Input::get('location') : null;
			//Check for income variables

            $case->referralsource_id = Input::get('inputreferrals');


			$check_is_activity = Activities::where('campaign_name','=',Input::get('inputfindus'))->where('practice_id',Auth::user()->practice->id)->first();
			if (Input::get('inputfindus')=='doctor_referral'){
				$newpatient->is_doctor_referral=1;
				$newpatient->how_found_id=0;
			}elseif(!is_null($check_is_activity)) {
                $case->activity()->associate($check_is_activity);
				$newpatient->is_activities_type = 0;
			}else {
                $case->activity_id = Input::get('inputfindus');
				$newpatient->is_activities_type = 1;
			}
           
           // $newpatient->how_found_id = Input::get('inputfindus');
            $case->is_scheduled = Input::get('inputscheduled');
            $validator = Validator::make(
                    array(
                        'name' => $newpatient->name,
                        'phone' => $newpatient->phone,
                        'referral_source_id' => $case->referralsource_id,
                        'activity_id' => $case->activity_id,
                        'is_scheduled' => $case->is_scheduled,
                        'location' => $newpatient->practice_location_id,
                        'diagnosis_id' => $newpatient->diagnosis_id,
                    ),
                    array(
                        'name' => 'required',
                        'phone' => 'required',
                        'referral_source_id' => 'required',
                        'activity_id' => 'required',
                        'is_scheduled' => 'required|boolean',
                        'location' => 'required',
                        'diagnosis_id' => 'required',
                        ),
                    array(
                        'name.required' => ' Patient`s name is required!',
                        'phone.required' => ' Patient`s phone is required',
                        'referral_source_id.required' => ' Referral Source is required!',
                        'activity_id.required' => ' How did patient find us field is required!',
                        'is_scheduled.required' => ' Patient scheduling status is required!',
                        'location.required' => ' Location is required!',
                        'diagnosis_id.required' => ' Diagnosis is required'
                    )
            );
			if(is_null($case->activity_id) && is_null($case->referral_source_id)) return 'Referral Source is required!';
            if ($validator->fails())
            {
                return $validator->errors()->all();
            } else
            {
                $thisuser = User::find(Auth::id());
                $newpatient->practice_id = $thisuser->practice_id;
                $case->insurance_id = null;
                $newpatient->value = '0'; //hardcoded
                $newpatient->diag_desc = '';
                $newpatient->notes = '';
                //$newpatient->address = '{"data":"{\"address1\":\"\",\"address2\":\"\",\"city\":\"\",\"state\":\"\",\"zip\":\"\"}","is_array":true}';
                if($case->is_scheduled == '0')
                { 
                    $newpatient->reason_no_schedule = Input::get('inputreasonnotscheduled');
                } else
                {
                    $newpatient->reason_no_schedule = null;
                }
                $case->save();
                if ($case->id)
                {
                    $newpatient->save();
                    if ($newpatient->id)
                    {
                        $case->patient()->associate($newpatient);
                        $case->save();
                    } else {
                        return "Error saving new patient";
                    }
                } else {
                    return "Error saving patient's case";
                }
                return 'successsavinguser';
            }
        }
    }

	public function postQuickaddreferral()
	{
		if (Request::ajax())
		{
			$referralSource = new ReferralSource;
            $referralSource->name = Input::get('doctornamedetails');
            $officeLocation = Auth::user()->practice->officeLocations()->first();

			$validator = Validator::make(
				array('cname' => Input::get('clinicnamedetails'),
					'rname' => $referralSource->name
				),
				array('cname' => 'required',
					'rname' => 'required'
				),
				array(
					'cname.required' => 'Office Name is required!',
					'rname.required' => 'Doctor name is required!'
				)
			);
			$existref = ReferralSource::where('name', '=', $referralSource->name)->count();

			if($existref > 0){
				return 'This doctor already exist!';
			}


			if ($validator->fails())
			{
				return $validator->errors()->all();
			} else
			{
				$referringOffice = new ReferringOffice();
                $referringOffice->practice()->associate(Auth::user()->practice);
                $referringOffice->name = Input::get('clinicnamedetails');
                $referringOffice->phone = '';
                $referringOffice->fax = '';
                $referringOffice->website = '';
                $referringOffice->address = '';
                $referringOffice->save();

                $referralSource->referralOffice()->associate($referringOffice);
                $referralSource->officeLocation()->associate($officeLocation);
                $referralSource->save();

				return 'successsavinguser';
			}
		}
	}

	public function postRefreshreferralcombobox()
	{
		if (Request::ajax())
		{
            $referralsource=Auth::user()->practice->referralSources;
            $all_activities=Auth::user()->practice->activities;
			$doctorname=Input::get('doctornamedetails');
			return View::make('site/patient/referralcombobox')->with('doctorname', $doctorname)->with('referralsource', $referralsource)->with('all_activities', $all_activities);
		} else
		{
			return false;
		}
	}

    // Get Patients Filter list 
    public function postFilterslist()
    {
        if (Request::ajax())
        {
            if (Input::get('getfilters') == '1')
            {
                $user = Auth::user();
                $firstobjjson = json_decode ($user->filters_patient);
                $filters = json_decode($firstobjjson->data, $firstobjjson->is_array);
				$doctors = $user->practice->referralSources;
                return View::make('site/patient/filters')->with('filters', $filters)->with('doctors', $doctors);
            } else
            {
                return false;
            }
        } else
        {
            return false;
        }
    }
    //save filters to database
    public function postSavefilters()
    {
        if (Request::ajax())
        {
			$datatoblob = json_decode(Input::get('selectedvalues'), true);
             //serialization
            $checkuserid = Auth::id();
            $autheduser = User::find(Auth::id());
            $objforuser = new stdClass;
            $objforuser->data = json_encode($datatoblob);
            $objforuser->is_array = is_array($datatoblob); // doing this for proper deserialize
            $objforuser_json = json_encode($objforuser);
            $autheduser->filters_patient = json_encode($objforuser);
            if ($autheduser->forceSave())
            {
                return 'success';
            }else
            {
                return 'nope';
            }
        } else
        {
            return false;
        }
    }
    // live searching by ajax
    public function anySearch()
    {
        return "something";
    }
       
    public function postOnepatient()
    {
            if (Request::ajax())
            {
                $thepatient = Patient::where('id', Input::get('id'))->get();
                $thepatientforaddress = Patient::where('id', Input::get('id'))->get(array('address'))->toArray();
                $firstobjjsonpat = json_decode ($thepatientforaddress['0']['address']);
                $address = json_decode($firstobjjsonpat->data, $firstobjjsonpat->is_array);
                return View::make('site/patient/patientdetails')->with('thepatient', $thepatient)->with('address', $address);
            }      
    }

    public function postCheckin()
    {
        if (Request::ajax())
        {
            $patient = Patient::findOrFail(Input::get('patient_id'));
            if (Auth::user()->isValidatedToPractice($patient->practice->id))
            {
                $patient->first_appointment = new DateTime();
                $patient->save();
                return "successsavinguser";
            } else {
                return Lang::get('messages.error_user_patient_validation');
            }
        }
    }
    public function postOnepatientbyname()
    {
            if (Request::ajax())
            {
                $thepatient = Patient::where('name', trim(Input::get('name')))->get(array('id'))->toArray();
                return $thepatient['0']['id'];
            }      
    }

    public function postDelete()
    {
        if (Request::ajax())
        {
            if (Auth::user())
            {
                $patient = Patient::findOrFail(Input::get('id'));
                if (Auth::user()->isValidatedToPractice($patient->practice->id))
                {
                    try
                    {
                        $patient = Patient::findOrFail(Input::get('id'));
                        foreach ($patient->cases as $case)
                        {
                            $case->delete();
                        }
                        return Response::json($patient->delete());
                    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
                    {
                        return Lang::get('message.error_patient_not_found');
                    } catch (Exception $e)
                    {
                        return $e->getMessage();
                    }
                } else 
                {
                    return Lang::get('messages.error_user_patient_validation');
                }
            }
        }
    }
    public function PatientsNamesList($token)
    {
        if (Request::ajax())
        {
            $thisuser = User::find(Auth::id());
            $patients = Patient::where('practice_id', '=', $thisuser->practice_id)->where('name', 'LIKE', '%'.$token.'%')->get(array('name','id'));
            return Response::json($patients);
        }
        
    }
    
    public function getPatientID($token)
    {
        if (Auth::check())
        {
            $user = Auth::user();
            $practice = $user->practice;
            $referralsource= $practice->referralSources;
            $all_activities= $practice->activities;
            $arr_insurance=$practice->insurances;
            $arr_therapists=$practice->therapists;
            $arr_activitytypes=$practice->activityTypes;


            $arr_diagnoses = $practice->diagnosis();
            $reasons = $practice->reasons();
            $patient = Patient::where('id', $token)->where('practice_id',$practice->id)->get();
            $arr_locations= $practice->officeLocations;
            $referringOffices = $practice->referringOffices;

            return View::make('site/patient/patientdetailsget')
				->with('thepatient', $patient)
				->with('referralsource', $referralsource)->with('all_activities', $all_activities)
				->with('arr_activitytypes', $arr_activitytypes)
				->with('arr_therapists', $arr_therapists)
				->with('arr_insurance', $arr_insurance)
				->with('arr_locations', $arr_locations)
				->with('arr_diagnoses', $arr_diagnoses)
                ->with('referralOffices', $referringOffices)
                ->with('reasons', $reasons);
        } else
        {
            return false;
        }
    }
    // save/update detailed patient
    public function postUpdatedetailedpatient()
    {
        if (Request::ajax())
        {
            $thisuser = User::find(Auth::id());
            $thispatient = Patient::find(Input::get('patientid'));
            if (Auth::user()->isValidatedToPractice($thispatient->practice->id))
            {
                if (Input::get('patientname') == '')
                {
                    return 'neednameatleast';
                } else
                {
                    $validation = Validator::make(
                        array(
                            'location' => Input::get('patientclinic'),
                        ),
                        array(
                            'location' => 'required',
                        ),
                        array(
                            'location.required' => 'Location is required!',
                        )
                    );
                    $thispatient->name = Input::get('patientname');
                    //$thispatient->insurance_id = Input::get('patientinsurance');

                    if (Input::get('patientclinic') != '')
                    {
                        $thispatient->practice_location_id = Input::get('patientclinic');
                    } else
                    {
                        $thispatient->practice_location_id = null;
                    }
                    $thispatient->phone = Input::get('patientphonedetail');
                    $thispatient->email = Input::get('patientemail');
    				
    				if(Input::has('patientbirth'))
    				{
    					$dob = new DateTime(Input::get('patientbirth'));
    					$thispatient->date_of_birth = date_format($dob, 'Y-m-d');
    				} else {
                        $thispatient->date_of_birth = null;
                    }
                   
                    $thispatient->diag_desc = Input::get('patientdiagnos');
                    
                    $thispatient->sex = Input::get('patientsex');
                    $thispatient->employer = Input::get('patientemployer');
                    $thispatient->workstatus = Input::get('patientworkstatus');
                    $thispatient->occupation = Input::get('patientoccupation');
                    $thispatient->notes = Input::get('patientnotes');
                    $thispatient->family_orients = Input::get('patientfamily');

    				$thispatient->address1 = Input::get('address1');
    				$thispatient->address2 = Input::get('address2');
    				$thispatient->city = Input::get('city');
    				$thispatient->state = Input::get('state');

    				if (Input::get('zip') != '')
    				{
    					$thispatient->zip = Input::get('zip');
    				} else
    				{
    					$thispatient->zip = null;
    				}
                    if ($validation->fails())
                    {
                        return $validation->errors()->all();
                    } else {
                        if ($thispatient->save())
                        {
                            return 'success';
                        } else
                        {
                            return false;
                        }
                    }

                }
            } else {
                return Lang::get('messages.error_user_patient_validation');
            }
        }
    }

// end of patients controller
}
