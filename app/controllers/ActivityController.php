<?php

use Illuminate\Database\QueryException;

class ActivityController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function showWelcome()
    {
        return View::make('site/layouts/default');
    }
	
	//postActivitylist
    // @return template with data
    public function anyActivitylist()
    {
        if (Request::ajax())
        {
                $activities = Auth::user()->practice->activities()->orderBy('created_at', 'desc');
                $allpatsforuser = $activities->paginate(50);

                return View::make('site/activity/activitytable')->with('patients', $allpatsforuser);
        } else
        {
            return false;
        }
    }
	
	public function postAddactivity()
    {
        if (Request::ajax())
        {
			$date = DateTime::createFromFormat('m/d/y',Input::get('date'));
            $newactivity = new Activities;
            $newactivity->campaign_name = Input::get('campaigname');
            $newactivity->activity_type_id = Input::get('activity_type_id');
            $newactivity->description = Input::get('description');
            $newactivity->cost = str_replace(',','',Input::get('costinput'));
            $newactivity->created_at = date_format($date, 'Y-m-d');
            $validator = Validator::make(
                    array('campaign_name' =>  $newactivity->campaign_name,
                    'activity_type_id' => $newactivity->activity_type_id,
                    'description' =>  $newactivity->description,
                    'cost' =>  $newactivity->cost,
                    'created_at' =>  $newactivity->created_at,
                    ),
                    array('campaign_name' => 'required', 
                    'activity_type_id' => 'required',
                    'description' => 'required',
                    'cost' => 'required',
                    'created_at' => 'required'
                    ),
                    array(
                        'campaign_name.required' => ' Campaign name is required!',
                        'activity_type_id.required' => ' Activity Type is required',
                        'description.required' => ' Description is required!',
                        'cost.required' => ' Cost field is required!',
                        'created_at.required' => ' Date is required!'
                    )
            );

            if ($validator->fails())
            {
                return $validator->errors()->all();
            } else
            {
                $thisuser = User::find(Auth::id());
                $newactivity->practice_id = $thisuser->practice_id;
                $newactivity->save();
                return 'successsavinguser';
            }
        }
    }

	public function postQuickaddactivitytype()
	{
		if (Request::ajax())
		{
			$activity = new ActivityTypes;
            $activity->name = Input::get('activitynamedetails');

			$validator = Validator::make(
				array('activityname' => $activity->name,
				),
				array('activityname' => 'required',
				),
				array(
					'activityname.required' => 'Clinic name is required!',
				)
			);

            $activity->practice()->associate(Auth::user()->practice);


			if ($validator->fails())
			{
				return $validator->errors()->all();
			} else
			{
                $activity->name = $activity->name;

                $activity->save();

				return 'successsavinguser';
			}
		}
	}
	
	public function postRefreshactivitycombobox()
	{
		if (Request::ajax())
		{
            $activityname=Input::get('activitynamedetails');
			if(empty($activityname)){
				$activityname=null;
			}
			return View::make('site/activity/activitycombobox')->with('activityname', $activityname);
		} else
		{
			return false;
		}
	}

    public function postDelete()
    {
        if (Request::ajax())
        {
            try {
                $activity = Activities::findOrFail(Input::get('activity_id'));
                if (Auth::user()->isValidatedToPractice($activity->practice->id))
                {
                    $activity->delete();
                    return 'success';
                } else {
                    return Lang::get('messages.error_user_activity_validation');
                }
            } catch (QueryException $e)
            {
                $name = '<ul>';
                $patient_ids = Cases::where('activity_id', Input::get('activity_id'))->lists('patient_id');
                $patient_ids = array_unique($patient_ids);
                $patients = Patient::whereIn('id', $patient_ids)->get();
                foreach ($patients as $patient)
                {
                    $name .= '<li>' . $patient->name . '</li>';
                }
                $name .= '</ul>';
                return 'This activity is assigned to patients, before you can delete this Activity, you must make sure it is not assigned to the patients listed:' . $name;
            }

        }
    }

    public function postUpdate()
    {
        if (Request::ajax())
        {
            $activity = Activities::findOrFail(Input::get('activity_id'));
            if (Auth::user()->isValidatedToPractice($activity->practice->id))
            {
                $activity->campaign_name = Input::get('campaign_name');
                $activity->cost = Input::get('cost');
                $activity->description = Input::get('description');
                $activity->created_at = new \Carbon\Carbon(Input::get('created_at'));
                $activity->activity_type_id = Input::get('activity_type_id');
                $activity->save();
                return 'success';
            } else {
                return Lang::get('messages.error_user_activity_validation');
            }
        }
    }

}
