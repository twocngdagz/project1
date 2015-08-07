<?php


class AdminController extends BaseController {

	public function postAccountCreation(){
		if(Auth::user()->role == 'admin'){
			if (Request::ajax())
			{
				$validator = Validator::make(Input::all(),
					array(
						'password'              	=> 'required|min:5',
						'password_confirmation' 	=> 'required|same:password',
						'companyname'              	=> 'required|min:3',
						'companyaddress' 			=> 'required',
						'companyphone'             	=> 'required',
						'companywebsite' 			=> 'url',
						'user_name' 				=> 'required|min:2',
						'email'              		=> 'required|email'
					),
					array(
						'password.required' => ' <br>Password is required!<br>',
						'password_confirmation.required' => ' Password confirmation is required! (password > 5 symbols)<br>',
						'companyname.required' => ' Company name is required!<br>',
						'companyaddress.required' => ' Company address is required!<br>',
						'companyphone.required' => ' Company phone is required!<br>',
						'email.required' => ' E-mail administrator is required!<br>',
						'companywebsite.url' => ' Wrong format of the website! http://example.com<br>',
						'user_name.required' => ' Name is required!<br>'
					)
				);

				if ($validator->fails())
				{
					return $validator->errors()->all();
				} else
				{
					$user = new User;
                    $user->name = Input::get('user_name');
                    $user->email = Input::get('email');
                    $user->password = Input::get('password');
                    $user->password_confirmation = Input::get('password_confirmation');
                    $user->password = Hash::make($user->password);
                    $user->confirmed = true;
                    $user->role = 'manager';
					// hardcoding for now default columns
                    $user->columns_patient = '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"phoneclm\":\"false\"}","is_array":true}';
                    $user->filters_patient = '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}';
                    $user->forceSave();

					$practice = new Practice;
                    $practice->name = Input::get('companyname');
                    $practice->save();

                    $user->practice()->associate($practice);
                    $user->forceSave();

					$office= new OfficeLocation;
                    $office->phone=Input::get('companyphone');
                    $office->fax=Input::get('companyfax');
                    $office->website=Input::get('companywebsite');
                    $office->address=Input::get('companyaddress');
                    $office->name=$practice->name;
                    $office->practice()->associate($practice);
                    $office->save();

					return 'successsavinguser';
				}
			}else{
				return View::make('site.admin.accountcreation');
			}
		}else{
			return Redirect::to('/');
		}
	}

    public function editDiagnosis($practice_id) {
        $practice = Practice::findOrFail($practice_id);
        $diagnosis = $practice->diagnosis($practice_id);
        return View::make('site.admin.diagnosis', compact('diagnosis', 'practice_id'));
    }

    public function saveDiagnosis() {
        $values = Input::get('data');
        foreach($values as $val)
        {
            if ($val['id'] != '')
            {
                $diagnosis = Diagnoses::findOrFail($val['id']);
                $diagnosis->name = $val['value'];
                $diagnosis->save();
            } else {
                $practice = Practice::findOrFail(Input::get('practice_id'));
                $diagnosis = new Diagnoses();
                $diagnosis->name = $val['value'];
                $diagnosis->company_id = $practice->id;
                $diagnosis->save();
            }

        }
        return 'successsavingdiagnosis';
    }

    public function saveInsurance()
    {
        $values = Input::get('data');
        foreach ($values as $val)
        {
            if ($val['id'] != '')
            {
                $insurance = Insurance::findOrFail($val['id']);
                $insurance->name = $val['value'];
                $insurance->save();
            } else {
                $practice = Practice::findOrFail(Input::get('practice_id'));
                $insurance = new Insurance();
                $insurance->name = $val['value'];
                $insurance->practice_id = $practice->id;
                $insurance->save();
            }
        }
        return 'successsavinginsurance';
    }

    public function saveReason()
    {
        $values = Input::get('data');
        foreach ($values as $val)
        {
            if ($val['id'] != '')
            {
                $reasons = ReasonNotScheduled::findOrFail($val['id']);
                $reasons->description = $val['value'];
                $reasons->save();
            } else {
                $practice = Practice::findOrFail(Input::get('practice_id'));
                $reasons = new ReasonNotScheduled();
                $reasons->description = $val['value'];
                $reasons->practice_id = $practice->id;
                $reasons->save();
            }
        }
        return 'successsavingreason';
    }

    public function saveLocation()
    {
        $values = Input::get('data');
        foreach ($values as $val)
        {
            if ($val['id'] != '')
            {
                $location = OfficeLocation::findOrFail($val['id']);
                $location->name = $val['value'];
                $location->save();
            } else {
                $practice = Practice::findOrFail(Input::get('practice_id'));
                $location = new OfficeLocation();
                $location->name = $val['value'];
                $location->practice_id = $practice->id;
                $location->save();
            }
        }

        return 'successsavinglocation';
    }

    public function deleteDiagnosis() {
        $diagnosis_id = Input::get('diagnosis_id');
        $practice_id = Input::get('practice_id');
        $diagnosis = Diagnoses::findOrFail($diagnosis_id);
        $diagnosis->delete();
        return Redirect::to(action('AdminController@editDiagnosis', array('practice_id'=>$practice_id)));

    }

    public function deleteInsurance() {
        $insurance_id = Input::get('insurance_id');
        $practice_id = Input::get('practice_id');
        $insurance = Insurance::findOrFail($insurance_id);
        $insurance->delete();
        return Redirect::to(action('AdminController@editInsurance', array('practice_id'=>$practice_id)));

    }

    public function deleteReason() {
        $reason_id = Input::get('reason_id');
        $practice_id = Input::get('practice_id');
        $reason = ReasonNotScheduled::findOrFail($reason_id);
        $reason->delete();
        return Redirect::to(action('AdminController@editReason', array('practice_id'=>$practice_id)));

    }

    public function deleteLocation()
    {
        $location_id = Input::get('location_id');
        $practice_id = Input::get('practice_id');
        $location = OfficeLocation::findOrFail($location_id);
        $location->delete();
        return Redirect::to(action('AdminController@editLocation', array('practice_id'=>$practice_id)));
    }

    public function editInsurance($practice_id) {
        $practice = Practice::findOrFail($practice_id);
        $insurances = $practice->insurances;
        return View::make('site.admin.insurance', compact('insurances', 'practice_id'));
    }

    public function editReason($practice_id) {
        $practice = Practice::findOrFail($practice_id);
        $reasons = $practice->reasons();
        return View::make('site.admin.reason', compact('reasons', 'practice_id'));
    }

    public function editLocation($practice_id)
    {
        $practice = Practice::findOrFail($practice_id);
        $locations = $practice->officeLocations;
        return View::make('site.admin.location', compact('locations', 'practice_id'));
    }

    public function getPractice()
    {
        $practices = Practice::all();
        return View::make('site.admin.practice', compact('practices'));
    }

    public function showUsers($practice_id)
    {
        $practice = Practice::findOrFail($practice_id);
        $users =  $practice->users;
        return View::make('site.admin.user', compact('users','practice_id'));
    }

    public function createUser($practice_id)
    {
        return View::make('site.admin.user_create')->with('practice_id', $practice_id);
    }

    public function storeUser()
    {
        if (Auth::user()->role == 'admin') {
            if (Request::ajax())
            {
                $validator = Validator::make(Input::all(),
                    array(
                        'password'              	=> 'required|min:5',
                        'password_confirmation' 	=> 'required|same:password',
                        'name' 				        => 'required|min:2',
                        'email'              		=> 'required|email'
                    ),
                    array(
                        'password.required' => ' <br>Password is required!<br>',
                        'password_confirmation.required' => ' Password confirmation is required! (password > 5 symbols)<br>',
                        'email.required' => ' E-mail administrator is required!<br>',
                        'name.required' => ' Name is required!<br>'
                    )
                );

                if ($validator->fails())
                {
                    return $validator->errors()->all();
                } else
                {
                    $user = new User;
                    $user->name = Input::get('name');
                    $user->email = Input::get('email');
                    $user->password = Input::get('password');
                    $user->password_confirmation = Input::get('password_confirmation');
                    $user->password = Hash::make($user->password);
                    $user->confirmed = true;
                    $user->role = 'manager';
                    // hardcoding for now default columns
                    $user->columns_patient = '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\" ,\"phoneclm\":\"false\"}","is_array":true}';
                    $user->filters_patient = '{"data":"{\"scheduledyes\":\"false\",\"scheduledno\":\"false\",\"showedupyes\":\"false\",\"showedupno\":\"false\",\"drnobody\":\"false\",\"drsomebody\":\"false\"}","is_array":true}';
                    $practice = Practice::findOrFail(Input::get('practice_id'));
                    $user->practice()->associate($practice);
                    $user->forceSave();
                    return 'successsavinguser';
                }
            }
        }
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return View::make('site.admin.user_edit', compact('user'));

    }


    public function updateUser()
    {
        $user = User::findOrFail(Input::get('user_id'));
        $user->name = Input::get('name');
        $user->email = Input::get('email');
        if (Input::get('password') != '')
        {
            $user->password = Hash::make(Input::get('password'));
        }
        $user->forceSave();
        return 'successsavinguser';
    }

    public function removeUser()
    {
        $user = User::findOrFail(Input::get('user_id'));
        if ($user->role == 'admin')
        {
            return Redirect::to(action('AdminController@showUsers', array('practice_id'=>Input::get('practice_id'))));
        } else {
            $user->delete();
            return Redirect::to(action('AdminController@showUsers', array('practice_id'=>Input::get('practice_id'))));
        }

    }

    public function checkColumn($column, $source)
    {
        switch ($column)
        {
            case 'name':
                if ( (isset($source['first_name']) && isset($source['last_name']))  ||    (isset($source['lastname']) && isset($source['firstname'])) )
                {
                    return true;
                }
                break;
            case 'phone':
                if ( (isset($source[$column])))
                {
                    return true;
                }
                break;
            case 'website':
                if ((isset($source['URLWebsite__c'])))
                {
                    return true;
                }
                break;
            case 'fax':
                if ((isset($source['fax'])))
                {
                    return true;
                }
                break;
            case 'address':
                if ((isset($source['address_1'])) || isset($source['address_2']) || isset($source['mailingstreet'])) {
                    return true;
                }
                break;
        }
        return false;

    }

    public function getValue($column, $source)
    {
        switch ($column)
        {
            case 'name';
                if (isset($source['first_name']) && isset($source['last_name'])) {
                        return ucfirst($source['first_name']) . ' ' . ucfirst($source['last_name']);
                } else {
                        return ucfirst($source['firstname']) . ' ' . ucfirst($source['lastname']);
                }
                break;
            case 'phone':
                return $this->checkColumn($column,$source) ? $source[$column] : '';
                break;
            case 'fax':
                return $this->checkColumn($column, $source) ? $source[$column] : '';
                break;
            case 'website':
                return $this->checkColumn($column, $source) ? $source['URLWebsite__c'] : '';
                break;
            case 'address':
                if ($this->checkColumn($column, $source))
                {
                    if (isset($source['address_1']) && isset($source['address_2']) && isset($source['mailingstreet']))
                    {
                        return $source['address_1'] .' '. $source['address_2'] .' '. $source['mailingstreet'];
                    } elseif (isset($source['address_1']) && isset($source['address_2']))
                    {
                        return $source['address_1'] .' '. $source['address_2'];
                    } elseif (isset($source['address_1']))
                    {
                        return $source['address_1'];
                    } elseif(isset($source['address_1']) && isset($source['mailingstreet']))
                    {
                        return $source['address_1'] .' '. $source['mailingstreet'];
                    }
                    else {
                        return $source['mailingstreet'];
                    }
                }
                break;

        }
    }


    public function importReferralSource()
    {
        set_time_limit(3000);
        $results = array();
        $isValid = false;
        $ext = Input::file('file')->getClientOriginalExtension();
        $directory = public_path();
        $filename = sha1(time().time()).".{$ext}";
        $upload_success = Input::file('file')->move($directory, $filename);
        if ($upload_success)
        {
            Excel::load($filename, function($reader) use (&$results, &$isValid) {
                $results = $reader->get();
                $first_row = $reader->first()->toArray();
                if ($this->checkColumn('name', $first_row))
                {
                    $isValid = true;
                }
            });
            File::delete($filename);
            if ($isValid){
                $totalRecords = $results->count();
                $counter = 0;
                foreach ($results as $source)
                {
                    $referralSource = new ReferralSource;
                    $referralSource->name = $this->getValue('name', $source);

                    $referringOffice = new ReferringOffice();
                    $referringOffice->practice()->associate(Practice::findOrFail(Input::get('practice_id')));
                    $referringOffice->name = $this->getValue('name', $source);
                    $referringOffice->phone = $this->getValue('phone', $source);
                    $referringOffice->fax = $this->getValue('fax', $source);
                    $referringOffice->website = $this->getValue('website', $source);
                    $referringOffice->address = $this->getValue('address', $source);
                    $referringOffice->save();
                    $referralSource->referralOffice()->associate($referringOffice);
                    $referralSource->save();
                    if ($referralSource->id)
                    {
                        $counter++;
                    }
                }
                $data = array('error'=> false, 'message'=> 'Successfully added all records ('.$counter.' of '.$totalRecords.').', 'data'=>$results);
                return Response::json($data);
            } else {
                $data = array('error'=> true, 'message'=> 'Columns First Name or Last Name not found', 'data'=>null);
                return Response::json($data);
            }
        }

        return 'error';

    }

}
