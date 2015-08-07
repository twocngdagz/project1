<?php

use Carbon\Carbon;

class DashboardController extends BaseController {

    // @input unixtimestamp
    // @return dateime
    public function toDateTimeFromUnixtimestamp($unixtime)
    {
        return date("Y-m-d H:i:s", $unixtime);
    }
    //revert
    public function toUnixtimestampFromDateTime($datetime)
    {
        $newdate = date_create($datetime);
        return date_format( $newdate, 'U');
    }

    public function postGetdashtable(){
        if (Request::ajax())
        {
            $templateofyear = array('Clinic', 'Calls', 'Scheduled New Patients', 'Conversion Rate %');
            $BeginDateTimePoint = date('Y-m-d', strtotime(Input::get('begin')));
            $EndDateTimePoint = date('Y-m-d', strtotime(Input::get('end')));
            // find out which date is startdate
            $firstmark = date('U', strtotime(Input::get('begin')));
            $secondmark = date('U', strtotime(Input::get('end')));
            if ($firstmark > $secondmark)
            {
                $BeginDateTimePoint = date('Y-m-d', strtotime(Input::get('end')));
                $EndDateTimePoint = date('Y-m-d', strtotime(Input::get('begin')));
            }

            //getting starting daterangepickering
            $start_date = date("Y-m-d", strtotime($BeginDateTimePoint));
            $end_date   = date("Y-m-d", strtotime($EndDateTimePoint));
            $start = strtotime($start_date . ' 00:00:00');
            $end = strtotime($end_date . ' 23:59:59');


            $practice = Auth::user()->practice;
            $a = 0;
            foreach($practice->officeLocations as $location)
            {
                $outputdatadash[$a]['Location'] = $location->name;
                $monthPatientsConverted = $practice->cases()
                    ->where('patient.practice_location_id', $location->id)
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($start))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($end))
                    ->whereNotNull('cases.first_appointment')
                    ->get(array('cases.created_at'))->toArray();
                $monthPatientsConvertedCount = count($monthPatientsConverted);
                $monthPatientsCalls = $practice->cases()
                    ->where('patient.practice_location_id', $location->id)
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($start))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($end))
                    ->get(array('cases.created_at'))->toArray();
                $monthPatientsCallsCount = count($monthPatientsCalls);
                $outputdatadash[$a]['calls'] = $monthPatientsCallsCount;
                $outputdatadash[$a]['scheduled'] =  $monthPatientsConvertedCount;
                $outputdatadash[$a]['rate'] = $monthPatientsCallsCount ? number_format(($monthPatientsConvertedCount / $monthPatientsCallsCount) * 100, 2) : 0;

                $a++;
            }
            return View::make('site/dashboard/dashboardtable')->with('data1', $outputdatadash)->with('headers', $templateofyear);
        }
    }


    // getting initial data for first chart by range
    public function postGetdashpatientschart()
    {
        if (Request::ajax())
        {
//            $dt = new Carbon(Input::get('begin'));
//            return $dt->lastOfMonth();
            //initial data for chart flot
            $outputdatadash = array();
            $outputdatadash[0] = array();
            $outputdatadash[0]['label'] = 'Conversion Percentage';
            $outputdatadash[0]['data'] = array();            
            
            $thisuser = User::find(Auth::id());
            // datetime range format Y-m-d - getting from js
            $BeginDateTimePoint = date('Y-m-d', strtotime(Input::get('begin')));
            $EndDateTimePoint = date('Y-m-d', strtotime(Input::get('end')));
            // find out which date is startdate
            $firstmark = date('U', strtotime(Input::get('begin')));
            $secondmark = date('U', strtotime(Input::get('end')));
            if ($firstmark > $secondmark)
            {
                $BeginDateTimePoint = date('Y-m-d', strtotime(Input::get('end')));
                $EndDateTimePoint = date('Y-m-d', strtotime(Input::get('begin')));
            }
            
            //getting starting daterangepickering 
            $start_date = date("Y-m-d", strtotime($BeginDateTimePoint));
            $end_date   = date("Y-m-d", strtotime($EndDateTimePoint));
            $start = strtotime($start_date);
            $end = strtotime($end_date);

            $month = $start;
            $months[] = date('Y-m', $start);
            while($month < $end) {
              $month = strtotime("+1 month", $month);
              $months[] = date('Y-m', $month);
            }

            foreach($months as $mon)
            {
                $mon_arr = explode( "-", $mon);
                $y = $mon_arr[0];
                $m = $mon_arr[1];
                $start_dates_arr[] = date("Y-m-d", strtotime($y.'-'.$m.'-01'.' 00:00:00'));
                $end_dates_arr[] = date("Y-m-d", strtotime('-1 minute', strtotime('+1 month',strtotime($y.'-'.$m.'-01'.' 00:00:00'))));
            }
            unset($mon);
            //to remove first month in start date and add our start date as first date
            array_shift($start_dates_arr);
            array_pop($start_dates_arr);
            array_unshift($start_dates_arr, $start_date);

            //to remove last month in end date and add our end date as last date
            array_pop($end_dates_arr);
            array_pop($end_dates_arr);
            array_push($end_dates_arr, $end_date);

            $result['start_dates'] = $start_dates_arr;
            $result['end_dates'] = $end_dates_arr;

            //counting range results
            $rangeresult = count($result['start_dates']);
            $practice = Auth::user()->practice;
            //picking up results from database
            for ($i = 0; $i < $rangeresult; $i++)
            {
                $firstpoint = $result['start_dates'][$i];
                $secondpoint = $result['end_dates'][$i];
                $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                $patients = $practice->cases()
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                    ->get(array('cases.created_at'))->toArray();
                
                $monthPatients = $practice->cases()
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                    ->whereNotNull('cases.first_appointment')
                    ->get(array('cases.created_at'))->toArray();
                $monthPatientsCount = count($monthPatients);
                $outputdatadash[0]['data'][] = array(($firstpointday*1000), $monthPatientsCount ? number_format($monthPatientsCount/count($patients) * 100, 2) : 0);
            }
            // sending result to js code
            return Response::json($outputdatadash);
        }
    }

	public function postGetdashpiereason()
	{
		if (Request::ajax())
		{
			//initial data for chart flot
			$outputdatadash = array();
			$reason_hold=array();

			$thisuser = User::find(Auth::id());
			// datetime range format Y-m-d - getting from js
			$BeginDateTimePoint = date('Y-m-d', strtotime(Input::get('begin')));
			$EndDateTimePoint = date('Y-m-d', strtotime(Input::get('end')));
			// find out which date is startdate
			$firstmark = date('U', strtotime(Input::get('begin')));
			$secondmark = date('U', strtotime(Input::get('end')));
			if ($firstmark > $secondmark)
			{
				$BeginDateTimePoint = date('Y-m-d', strtotime(Input::get('end')));
				$EndDateTimePoint = date('Y-m-d', strtotime(Input::get('begin')));
			}

			//getting starting daterangepickering
			$start_date = date("Y-m-d", strtotime($BeginDateTimePoint));
			$end_date   = date("Y-m-d", strtotime($EndDateTimePoint));
			$firstpointday = strtotime($start_date);
			$secondpointday = strtotime($end_date);

			//picking up results from database

            $reasons = Auth::user()->practice->reasons();
            $practice = Auth::user()->practice;
            foreach ($reasons as $reason)
            {
                $patients = $practice->cases()
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->where('cases.reasonnotscheduled_id', $reason->id)->get();
                $outputdatadash[]=array('label'=>$reason->description,'data'=>count($patients));
            }


			// sending result to js code
			return Response::json($outputdatadash);
		}
	}

}
