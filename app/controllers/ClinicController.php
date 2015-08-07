<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClinicController extends BaseController {

    protected $practice;
    public function __construct(Practice $practice)
    {
        parent::__construct();
        $this->$practice = $practice;
    }
    
    //getIndex
    public function getIndex()
    {
        $practice = Auth::user()->practice;
        return View::make('site/clinic/clinic')->with('clinics', $practice);;
    }
	
	//getIndex
    public function getDetails($id)
    {
        try
        {
            $referralSource = ReferralSource::findOrFail($id);
        } catch (ModelNotFoundException $e)
        {
            $referralSource = null;
        }
        if ($referralSource)
        {
            $referringOffice = $referralSource->referralOffice;
            $practice = $referringOffice->practice;
            if ($practice->id != Auth::user()->practice->id)
            {
                $referralSource = null;
            }
        } else {
            $referringOffice = null;
            $practice = null;
        }
		$owner = Auth::user();
       return View::make('site/clinic/clinicdetails')->with('clinic', $practice)->with('owner', $owner)->with('referringOffice', $referringOffice)->with('referralSource', $referralSource);
    }
    
    //getallcomments
    // @return view+assigned data
    public function anyNoteslist()
    {
        if (Request::ajax())
        {
			if (Input::has('clinicid')) {
                $referringOffice = ReferringOffice::find(Input::get('clinicid'));
                $notes = $referringOffice->notes();
                $allnotesfordefaultclinic = $notes->orderBy('created_at', 'desc')->paginate(10)->toJson();
                return View::make('site/clinic/clinicnotes')->with('clinicnotes', $allnotesfordefaultclinic);
            }
        } else
        {
            return false;
        }
        
    }
    
    //get all tasks for clinic
    //@obj 
    public function postTaskslist()
    {
        if (Request::ajax())
        {
            if (Input::has('clinicid'))
            {
                $referringOffice = ReferringOffice::find(Input::get('clinicid'));
                $tasks = $referringOffice->tasks();
                $alltasks = $tasks->where('is_completed', '<>', true)->orderBy('created_at', 'desc')->get(); // task filters now hardcoded to admin all
                return View::make('site/clinic/clinictasks')->with('clinictasks', $alltasks);
            }
        } else
        {
            return false;
        }
    }
    
    // @input unixtimestamp
    // @return dateime
    public function toDateTimeFromUnixtimestamp($unixtime)
    {
        return date("Y-m-d", $unixtime);
        
    }
    //revert
    public function toUnixtimestampFromDateTime($datetime)
    {
        $newdate = date_create($datetime);
        return date_format( $newdate, 'U');
    }
    
    //getPatientslist for 12 monthes till now
    //@objs
    public function anyPatientslist()
    {
        if (Request::ajax())
        {
            $referralSource = ReferralSource::find(Input::get('clinicid'));
            //$thisuser = User::find(Auth::id());
//            $past12monthes = time() - (48 * 7 * 24 * 60 * 60);
            
            $allpatientsforthiscliniclast12monthes = $referralSource->cases()->orderBy('created_at', 'desc')->paginate(10)->toJson();
            return View::make('site/clinic/clinicpatients')->with('clinicpatients', $allpatientsforthiscliniclast12monthes);
        } else
        {
            return false;
        }
    }
    
    //set task completed
    //@string success
    public function postTaskcompleted()
    {
        if (Request::ajax())
        {
            $editedtask = Crmtasks::find(Input::get('tasktocomplete'));
            $editedtask->is_completed = '1';
            if ($editedtask->forceSave())
            {
                return 'success';
            } else
            {
                return false;
            }
        } else
        {
            return false;
        }
    }
    
    //adding new task
    // @var success or errors object
    public function postAddnewtask()
    {
        if (Request::ajax())
        {
            $newtask = new Crmtasks;
            $newtask->title = Input::get('tasktitle');
            $referringOffice = ReferringOffice::findOrFail(Input::get('clinicid'));
            if ($newtask->validate())
            {
                $newtask->user()->associate(Auth::user());
                $newtask->referringOffice()->associate($referringOffice);
                $newtask->assigned_to = '1'; //hardcoded to admin
                $newtask->updater_id = '1'; //hardcoded to admin
                $newtask->is_completed = '0'; //default task not complete
                if ($newtask->forceSave())
                {
                    return 'success';
                } else
                {
                    return false;
                }
            } else
            {
                return $newtask->errors()->all();
            }
        } else
        {
            return false;
        }
    }    
    
    // add note
    // @string success or not 
    public function postAddnote()
    {
        if (Request::ajax())
        {
            $note = new Crmnotes;
            $note->description = Input::get('newnote');
			$referringOffice = ReferringOffice::findOrFail(Input::get('clinicid'));
            if ($note->validate())
            {
                $note->referringOffice()->associate($referringOffice);
                $note->user()->associate(Auth::user());
                $note->save();
                return 'success';
            } else
            {
                return 'nope';
            }
        } else
        {
            return false;
        }
        
    }
  
  public function postGetcrmdatachart()
  {
      if (Request::ajax())
      {
          $outputdata = array();
          $outputdata[0] = array();
          
          $outputdata[0]['label'] = 'New Patients';
          $outputdata[0]['data'] = array();
          $thisuser = User::find(Auth::id());
		  $clinic_id = Input::get('clinicid');
          $referralSource = ReferralSource::findOrFail(Input::get('referral_source_id'));

          for($i = 12; $i > 0; $i--)
          {
              
              $firstpoint = $i - 1;
              $secondpoint = $i - 2;
              
              $firstpointday = $this->toUnixtimestampFromDateTime(date('Y-m-d', mktime(0, 0, 0, date('m') - $firstpoint, 1, date('Y'))));
              $secondpointday = $this->toUnixtimestampFromDateTime(date('Y-m-d', mktime(0, 0, 0, date('m') - $secondpoint, 1, date('Y'))));
              $monthPatients = $referralSource->cases()->where('created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))->where('created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))->get(array('created_at'))->toArray();
              $monthPatientsCount = count($monthPatients);
              
              $outputdata[0]['data'][] = array(($firstpointday*1000), $monthPatientsCount);
          }
      }
 
      $firstDayNextMonth = $this->toUnixtimestampFromDateTime(date('Y-m-d', mktime(0, 0, 0, date('m')+1, 1, date('Y'))));
      $firstDayThisMonth = $this->toUnixtimestampFromDateTime(date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))));
      $thisMonthPatients = $referralSource->cases()->where('created_at', '<=', $this->toDateTimeFromUnixtimestamp($firstDayNextMonth))->where('created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstDayThisMonth))->get(array('created_at'))->toArray();
      $thisMonthPatientsCount = count($thisMonthPatients);
      $outputdata[0]['data'][] = array(($firstDayNextMonth*1000), $thisMonthPatientsCount);
      return Response::json($outputdata);
  }

	public function postOneclinicbyname()
	{
		if (Request::ajax())
		{
			$theclinic = ReferralSource::where('name', trim(Input::get('name')))->get(array('id'))->toArray();
			return $theclinic['0']['id'];
		}
	}

	public function ClinicNamesList($token)
	{
		if (Request::ajax())
		{
			$clinic = ReferralSource::where('name', 'LIKE', '%'.$token.'%')->get(array('name'));
			return Response::json($clinic);
		}

	}

	public function postAddnewclinic()
	{
		if (Request::ajax())
		{
            $referralSource = new ReferralSource;
            $referralSource->name = Input::get('doctor');
			$validator = Validator::make(
				array('clinic_name' =>  Input::get('clinicname'),
					'clinic_doctor' =>  Input::get('doctor'),
				),
				array('clinic_name' => 'required',
					'clinic_doctor' =>  'required',
				),
				array(
					'clinic_name.required' => ' Office name is required!',
					'clinic_doctor.required' => ' Doctor name is required!',
				)
			);

			if ($validator->fails())
			{
				return $validator->errors()->all();
			} else
			{
                $referringOffice = new ReferringOffice();
                $referringOffice->practice()->associate(Auth::user()->practice);
                $referringOffice->name = Input::get('clinicname');
                $referringOffice->phone = Input::get('phone');;
                $referringOffice->fax = Input::get('fax');
                $referringOffice->website = Input::get('website');
                $referringOffice->address = Input::get('address');
                $referringOffice->save();

                $referralSource->referralOffice()->associate($referringOffice);
                $referralSource->save();
				return 'successsavinguser';
			}
		}
	}

	function getEditclinic($id){
        $referralSource = ReferralSource::findOrFail($id);
        $referringOffice = $referralSource->referralOffice;
		$clinic = $referringOffice->practice;
		return View::make('site/clinic/editclinic')->with('clinic', $clinic)->with('referralSource', $referralSource)->with('referringOffice', $referringOffice);
	}

    function getEditdoctor($id)
    {
        $referralSource = ReferralSource::findOrFail($id);
        return View::make('site/clinic/referralSource')->with('referralSource', $referralSource);
    }

	public function postUpdateclinic(){
        $referringOffice = ReferringOffice::findOrFail(Input::get('clinic_id'));
        $referringOffice->name = Input::get('clinic_name');
        $referringOffice->phone=Input::get('clinic_phone');
        $referringOffice->fax=Input::get('clinic_fax');
        $referringOffice->website=Input::get('clinic_website');
        $referringOffice->address=Input::get('clinic_address');
		$validator = Validator::make(
			array('clinic_name' =>  $referringOffice->name,
				'clinic_phone' =>  $referringOffice->phone,
				'clinic_address' =>  $referringOffice->address,
				'clinic_website' => $referringOffice->website,
			),
			array('clinic_name' => 'required',
				'clinic_phone' =>  'required',
				'clinic_address' =>  'required',
				'clinic_website' =>'url',
			),
			array(
				'clinic_name.required' => ' Office name is required!',
				'clinic_phone.required' => ' Office phone is required!',
				'clinic_address.required' => ' Office address is required!',
				'clinic_website.url' => ' Wrong format of the website! http://example.com',
			)
		);

		if ($validator->fails())
		{
			return Redirect::to('/clinic/editclinic/'.Input::get('referral_source_id'))->withErrors($validator);
		} else
		{
            $referringOffice->save();
			return Redirect::to('/clinic/details/id/'.Input::get('referral_source_id'))->with('info', 'Information about the referring office was changed');
		}
	}

    public function postUpdatedoctor() {
        $referralSource = ReferralSource::find(Input::get('doctor_id'));
        $referralSource->name = Input::get('doctor_name');
        $validator = Validator::make(
            array(
                'doctor_name' =>  $referralSource->name,
            ),
            array('doctor_name' => 'required',
            ),
            array(
                'clinic_name.required' => ' Doctor name is required!',
            )
        );

        if ($validator->fails())
        {
            return Redirect::to('/clinic/editdoctor/'.Input::get('doctor_id'))->withErrors($validator);
        } else
        {
            $referralSource->save();
            return Redirect::to('/clinic/details/id/'.Input::get('doctor_id'))->with('info', 'Information about the referring office was changed');
        }
    }

	function anyAllcliniclist(){
		if (Request::ajax())
		{
            $referralSources = Auth::user()->practice->referralSources()->paginate(10);
            $practice = Auth::user()->practice;
			return View::make('site/clinic/clinictable')->with('referralSources', $referralSources)->with('practice', $practice);
		} else
		{
			return false;
		}
	}

}
