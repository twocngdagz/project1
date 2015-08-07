<?php

class ReportController extends BaseController {
    
    public $inputdata;
    public function __construct()
    {
        $this->inputdata = new StdClass(); 
    }

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

    public function postInitclinicfilter()
    {
        if (Request::ajax())
        {
            $thisuser = User::find(Auth::id());
            $allclinics = Clinic::all();
            return View::make('site/report/clinicfilter')->with('clinics', $allclinics);
        }
    }
    
    public function postInitreferralfilter()
    {
        if (Request::ajax())
        {
            $thisuser = User::find(Auth::id());
            $allclinics = Clinic::all();
            $allreferrals = Referralgrid::all();
            return View::make('site/report/referralsource')->with('clinics', $allclinics)->with('referrals', $allreferrals);
        }
    }

    public function getReport()
    {
        $user = Auth::user();
        $practice = $user->practice;
        $diagnosis_arr = $practice->diagnosis();
        return View::make('site/report/report', compact('diagnosis_arr'));
    }
    
    public function dateRangeSorterByMonthes($starting, $ending)
    {
        $start = strtotime($starting);
        $end = strtotime($ending);

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
            $start_dates_arr[] = date("Y-m-d H:i:s", strtotime($y.'-'.$m.'-01'.' 23:59:59'));
            $end_dates_arr[] = date("Y-m-d H:i:s", strtotime('-1 minute', strtotime('+1 month',strtotime($y.'-'.$m.'-01'.' 23:59:59'))));
        }
        unset($mon);
        //to remove first month in start date and add our start date as first date
        array_shift($start_dates_arr);
        array_pop($start_dates_arr);
        array_unshift($start_dates_arr, $starting);

        //to remove last month in end date and add our end date as last date
        array_pop($end_dates_arr);
        array_pop($end_dates_arr);
        array_push($end_dates_arr, $ending);

        $result['start_dates'] = $start_dates_arr;
        $result['end_dates'] = $end_dates_arr;
        
        return $result;
    }
    
    public function dateRangeSorterByDays($fromDate, $toDate)
    {
        $dateRange = (array) $fromDate;
        $splitDate = explode ( '-', $fromDate );

        while ( $dateRange[ sizeof($dateRange)-1 ] != $toDate )
        {
            if ( ! checkdate( $splitDate[1], ++$splitDate[2], $splitDate[0] ) )
            {
                if ( ! checkdate( (int)++$splitDate[1], (int)$splitDate[2]=1, (int)$splitDate[0] ) ) 
                {
                $splitDate[2] = $splitDate[1] = 1;
                $splitDate[0]++;
                }
            }

            $dateRange[] = str_pad($splitDate[0], 4, "0", STR_PAD_LEFT) .'-'. str_pad($splitDate[1], 2, "0", STR_PAD_LEFT) .'-'. str_pad($splitDate[2], 2, "0", STR_PAD_LEFT);

        }

        return $dateRange; 
    }
    
    public function dateRangeSorterByWeeks($first, $last)
    {
        $step = '+1 week';
        $format = 'Y-m-d';
        $dates = array();
        $current = strtotime( $first );
        $last = strtotime( $last );

        while( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }
    public function postGetreferralstable()
    {
        if (Request::ajax())
        {
            $this->inputdata->startdate = date("Y-m-d", strtotime(Input::get('startdate')));
            $this->inputdata->enddate = date("Y-m-d", strtotime(Input::get('enddate')));
            $this->inputdata->timedivision = Input::get('timedivision');
            $this->inputdata->sortedRanges = '';
            $this->inputdata->enabledClinicsRef = json_decode(Input::get('clinicrefcodes'));
            $this->inputdata->enabledReferralsRef = json_decode(Input::get('referralrefcodes'));
            $this->inputdata->enabledClinicsMain = json_decode(Input::get('maincliniccodes'));
            $this->inputdata->enabledDiagnosis =json_decode(Input::get('diagnosiscodes'));
            $this->inputdata->enabledActivity =json_decode(Input::get('activitycodes'));
            $outputdataloc = array();
            $outputdataloc[0] = array();
            $includeNull = false;

            $this->doFilters();
            $patients = Auth::user()->practice->cases()->converted();
            if (count($this->inputdata->enabledClinicsMain) != 0) {
                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
            }
            if (count($this->inputdata->enabledDiagnosis) != 0) {
                if (in_array(-1, $this->inputdata->enabledDiagnosis))
                {
                    unset($this->inputdata->enabledDiagnosis[count($this->inputdata->enabledDiagnosis) - 1]);
                    $includeNull = true;
                }
            }
            if (count($this->inputdata->enabledActivity) != 0) {
                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
            }

            if (count($this->inputdata->enabledClinicsMain) == 0 && count($this->inputdata->enabledDiagnosis) == 0 && count($this->inputdata->enabledActivity) == 0)
            {
                $outputdataloc=null;
                $templateofyear = array('Doctors', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total', '% Change');
                return View::make('site/report/referralstable')->with('data1', $outputdataloc)->with('headers', $templateofyear);
            }

            if ($this->inputdata->timedivision == 'year')
            {
                    $this->inputdata->sortedRanges = $this->dateRangeSorterByMonthes($this->inputdata->startdate, $this->inputdata->enddate);
                    $rangeresult = count($this->inputdata->sortedRanges['start_dates']);
                    $templateofyear = array('Doctors', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total', '% Change');
                    $a = 0;

                    $things = $rangeresult - 1;
                    $firstpoint = $this->inputdata->sortedRanges['start_dates'][0];
                    $secondpoint = $this->inputdata->sortedRanges['end_dates'][$things];
                    $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                    $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                    $referralSource = $patients
                        ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                        ->lists('referralsource_id');
                    $referral_source_ids = array_unique($referralSource);
                    if (count($referral_source_ids) != 0)
                    {
                        foreach ($referral_source_ids as $referral_source_id) {
                            $outputdataloc[$a]['Doctor'] = ReferralSource::findOrFail($referral_source_id)->name;

                            for ($i = 0; $i < $rangeresult; $i++)
                            { //dataget
                                $patients = Auth::user()->practice->cases()->converted();
                                if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                                if ($includeNull) {
                                    $patients->where(function($query) {
                                        $query->WhereNull('cases.diagnosis_id');
                                        if (count($this->inputdata->enabledDiagnosis) != 0)
                                        {
                                            $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                        }
                                    });
                                } else {
                                    $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                                if (count($this->inputdata->enabledActivity) != 0) {
                                    $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                                }
                                $firstpoint = $this->inputdata->sortedRanges['start_dates'][$i];
                                $secondpoint = $this->inputdata->sortedRanges['end_dates'][$i];
                                $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                                $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                                $yearPatients = $patients
                                    ->whereHas('ReferralSource', function($query) use($referral_source_id)
                                    {
                                        $query->where('cases.referralsource_id', $referral_source_id);
                                    })
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                    ->get(array('cases.created_at'))->toArray();
                                $outputdataloc[$a]['data'][$i] = count($yearPatients);
                            }

                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }


                            //getting total by year
                            $things = $rangeresult - 1;
                            $firstpoint = $this->inputdata->sortedRanges['start_dates'][0];
                            $secondpoint = $this->inputdata->sortedRanges['end_dates'][$things];
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            $tmpcount = count($patients
                                ->whereHas('ReferralSource',function($query) use($referral_source_id) {
                                    $query->where('cases.referralsource_id', $referral_source_id);
                                })
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray());
                            $outputdataloc[$a]['total'] = $tmpcount;
                            // getting change

                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }

                            $firstreal =  strtotime("-1 year", strtotime($this->inputdata->sortedRanges['start_dates'][0]));
                            $secondreal = strtotime("-1 year", strtotime($this->inputdata->sortedRanges['end_dates'][$things]));
                            $firstpoint = date("Y-m-d", $firstreal);
                            $secondpoint = date("Y-m-d", $secondreal);
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            $tmppastyearresult = count($patients
                                ->where('cases.referralsource_id', $referral_source_id)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray());
                            $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                            $a = $a + 1;
                        }
                    }
                    return View::make('site/report/referralstable')->with('data1', $outputdataloc)->with('headers', $templateofyear);
            }
            if ($this->inputdata->timedivision == 'week')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $templateofyear = array('Patients', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Total', '% Change');
                $a = 0;


                $things = $rangeresult - 1;
                $firstpoint = $this->inputdata->sortedRanges[0];
                $secondpoint = $this->inputdata->sortedRanges[$things];
                $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                $referralSource = $patients
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                    ->lists('cases.referralsource_id');
                $referral_source_ids = array_unique($referralSource);
                if (count($referral_source_ids) != 0)
                {
                    foreach ($referral_source_ids as $referral_source_id) {

                        $outputdataloc[$a]['Doctor'] = ReferralSource::findOrFail($referral_source_id)->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $firstpoint = $this->inputdata->sortedRanges[$i];
                            $lastDateWanted = $this->inputdata->sortedRanges[$i];
                            $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            $weekPatients = $patients
                                ->whereHas('ReferralSource', function($query) use($referral_source_id)
                                {
                                    $query->where('cases.referralsource_id', $referral_source_id);
                                })
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();
                            $outputdataloc[$a]['data'][$i] = count($weekPatients);
                        }
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }

                        //getting total by year
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges[0];
                        $secondpoint = $this->inputdata->sortedRanges[$things];
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmpcount = count($patients
                            ->whereHas('ReferralSource',function($query) use($referral_source_id) {
                                $query->where('cases.referralsource_id', $referral_source_id);
                            })
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());
                        $outputdataloc[$a]['total'] = $tmpcount;
                        // getting change

                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $firstreal =  strtotime("-1 week", strtotime($this->inputdata->sortedRanges[0]));
                        $secondreal = strtotime("-1 week", strtotime($this->inputdata->sortedRanges[$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmppastyearresult = count($patients
                            ->where('cases.referralsource_id', $referral_source_id)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());
                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        $a = $a + 1;
                    }
                }

                return View::make('site/report/referralstable')->with('data1', $outputdataloc)->with('headers', $templateofyear);
            }
            if ($this->inputdata->timedivision == 'month')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $templateofyear = array('Patients');
                for ($i = 1; $i < ($rangeresult + 1); $i++)
                {
                    $templateofyear[] = $i.'th '.date('M', $this->toUnixtimestampFromDateTime($this->inputdata->startdate));
                }
                $templateofyear[] = 'Total';
                $templateofyear[] =  '% Change';
                $a = 0;

                $things = $rangeresult - 1;
                $firstpoint = $this->inputdata->sortedRanges[0];
                $secondpoint = $this->inputdata->sortedRanges[$things];
                $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                $referralSource = $patients
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                    ->lists('cases.referralsource_id');
                $referral_source_ids = array_unique($referralSource);
                if (count($referral_source_ids) != 0)
                {
                    foreach ($referral_source_ids as $referral_source_id) {
                        $outputdataloc[$a]['Doctor'] = ReferralSource::findOrFail($referral_source_id)->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $firstpoint = $this->inputdata->sortedRanges[$i];
                            $lastDateWanted = $this->inputdata->sortedRanges[$i];
                            $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            $weekPatients = $patients
                                ->where('cases.referralsource_id', $referral_source_id)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();
                            $outputdataloc[$a]['data'][$i] = count($weekPatients);
                        }

                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }

                        //getting total by month
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges[0];
                        $secondpoint = $this->inputdata->sortedRanges[$things];
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmpcount = count($patients
                            ->whereHas('ReferralSource',function($query) use($referral_source_id) {
                                $query->where('cases.referralsource_id', $referral_source_id);
                            })
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());
                        $outputdataloc[$a]['total'] = $tmpcount;

                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id')
                                    ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            });
                        } else {$patients->where(function($query) {
                            $query->WhereNull('cases.diagnosis_id');
                            if (count($this->inputdata->enabledDiagnosis) != 0)
                            {
                                $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                        });
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        // getting change
                        $firstreal =  strtotime("-1 month", strtotime($this->inputdata->sortedRanges[0]));
                        $secondreal = strtotime("-1 month", strtotime($this->inputdata->sortedRanges[$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmppastyearresult = count($patients
                            ->where('cases.referralsource_id', $referral_source_id)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());
                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        $a = $a + 1;
                    }
                }
                return View::make('site/report/referralstable')->with('data1', $outputdataloc)->with('headers', $templateofyear);
            }
            if ($this->inputdata->timedivision == 'quarter')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByWeeks($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $templateofyear = array('Patients');
                for ($i = 1; $i < ($rangeresult + 1); $i++)
                {
                    $templateofyear[] = $i.'th w.';
                }
                $templateofyear[] = 'Total';
                $templateofyear[] =  '% Change';
                $a = 0;

                $things = $rangeresult - 1;
                $firstpoint = $this->inputdata->sortedRanges[0];
                $secondpoint = $this->inputdata->sortedRanges[$things];
                $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                $referralSource = $patients
                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                    ->lists('cases.referralsource_id');
                $referral_source_ids = array_unique($referralSource);
                if (count($referral_source_ids) != 0)
                {
                    foreach ($referral_source_ids as $referral_source_id) {
                        $outputdataloc[$a]['Doctor'] = ReferralSource::findOrFail($referral_source_id)->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $firstpoint = $this->inputdata->sortedRanges[$i];
                            $lastDateWanted = $this->inputdata->sortedRanges[$i];
                            $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            $weekPatients = $patients
                                ->whereHas('ReferralSource', function($query) use($referral_source_id)
                                {
                                    $query->where('cases.referralsource_id', $referral_source_id);
                                })
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();
                            $outputdataloc[$a]['data'][$i] = count($weekPatients);
                        }

                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        //getting total by quarter
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges[0];
                        $secondpoint = $this->inputdata->sortedRanges[$things];
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmpcount = count($patients
                            ->whereHas('ReferralSource',function($query) use($referral_source_id) {
                                $query->where('cases.referralsource_id', $referral_source_id);
                            })
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());
                        $outputdataloc[$a]['total'] = $tmpcount;
                        // getting change

                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $firstreal =  strtotime("-3 month", strtotime($this->inputdata->sortedRanges[0]));
                        $secondreal = strtotime("-3 month", strtotime($this->inputdata->sortedRanges[$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmppastyearresult = count($patients
                            ->where('cases.referralsource_id', $referral_source_id)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());
                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        $a = $a + 1;
                    }
                }
                return View::make('site/report/referralstable')->with('data1', $outputdataloc)->with('headers', $templateofyear);
            }

        } else
        {
            return false;
        }

    }
    public function postGetpracticetable()
    { // location table by daterange and filtered by pdf spec
        if (Request::ajax())
        {
            $thisuser = User::find(Auth::id());
            $this->inputdata->startdate = date("Y-m-d", strtotime(Input::get('startdate')));
            $this->inputdata->enddate = date("Y-m-d", strtotime(Input::get('enddate')));
            $this->inputdata->timedivision = Input::get('timedivision');
            $this->inputdata->sortedRanges = '';
            $this->inputdata->enabledClinicsRef = json_decode(Input::get('clinicrefcodes'));
            $this->inputdata->enabledReferralsRef = json_decode(Input::get('referralrefcodes'));
            $this->inputdata->enabledClinicsMain = json_decode(Input::get('maincliniccodes'));
            $this->inputdata->enabledDiagnosis = json_decode(Input::get('diagnosiscodes'));
            $this->inputdata->enabledActivity = json_decode(Input::get('activitycodes'));
            $outputdataloc = array();
            $outputdataloc[0] = array();
            $includeNull = false;
            $this->doFilters();
            
            
            if ($this->inputdata->timedivision == 'year')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByMonthes($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges['start_dates']);
                $templateofyear = array('Location', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total', '% Change');
                $a = 0;

                if (count($this->inputdata->enabledDiagnosis) != 0) {
                    if (in_array(-1, $this->inputdata->enabledDiagnosis))
                    {
                        unset($this->inputdata->enabledDiagnosis[count($this->inputdata->enabledDiagnosis) - 1]);
                        $includeNull = true;
                    }
                }

                
                if (count($this->inputdata->enabledClinicsMain) != 0)
                {
                    foreach ($this->inputdata->enabledClinicsMain as $clinicidy)
                    {
                        $officeLocation = OfficeLocation::findOrFail($clinicidy);
                        $outputdataloc[$a]['location'] = $officeLocation->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $firstpoint = $this->inputdata->sortedRanges['start_dates'][$i];
                            $secondpoint = $this->inputdata->sortedRanges['end_dates'][$i];
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            if ($includeNull) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                    ->where('practice_location_id', $clinicidy)
                                    ->where(function($query) {
                                        $query->WhereNull('cases.diagnosis_id');
                                        if (count($this->inputdata->enabledDiagnosis) != 0)
                                        {
                                            $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                        }

                                    });

                            } else {
                                if (count($this->inputdata->enabledDiagnosis) != 0) {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                        ->where('practice_location_id', $clinicidy)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                } else {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('practice_location_id', $clinicidy)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                }
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        }
         
                        //getting total by year
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges['start_dates'][0];
                        $secondpoint = $this->inputdata->sortedRanges['end_dates'][$things];
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('practice_location_id', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $outputdataloc[$a]['total'] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        // getting change
                        $firstreal =  strtotime("-1 year", strtotime($this->inputdata->sortedRanges['start_dates'][0]));
                        $secondreal = strtotime("-1 year", strtotime($this->inputdata->sortedRanges['end_dates'][$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('practice_location_id', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
                        
                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        
                        $a = $a + 1;
                    }
                }
                
                if ((count($this->inputdata->enabledActivity) == 0) && (count($this->inputdata->enabledClinicsMain) == 0) && count($this->inputdata->enabledDiagnosis) == 0)
                {
					$outputdataloc=null;
                }
           
                return View::make('site/report/locationtable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
                
            } // year ends
            if ($this->inputdata->timedivision == 'month')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);

                $templateofyear = array('Location');
                for ($i = 1; $i < ($rangeresult + 1); $i++)
                {
                    $templateofyear[] = $i.'th '.date('M', $this->toUnixtimestampFromDateTime($this->inputdata->startdate));
                }
                $templateofyear[] = 'Total';
                $templateofyear[] =  '% Change';
 
                $a = 0;

                if (count($this->inputdata->enabledDiagnosis) != 0) {
                    if (in_array(-1, $this->inputdata->enabledDiagnosis))
                    {
                        unset($this->inputdata->enabledDiagnosis[count($this->inputdata->enabledDiagnosis) - 1]);
                        $includeNull = true;
                    }
                }
                
                if (count($this->inputdata->enabledClinicsMain) != 0)
                {
                    foreach ($this->inputdata->enabledClinicsMain as $clinicidy)
                    {
                        $officeLocation = OfficeLocation::findOrFail($clinicidy);
                        $outputdataloc[$a]['location'] = $officeLocation->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $firstpoint = $this->inputdata->sortedRanges[$i];
                            $lastDateWanted = $this->inputdata->sortedRanges[$i];
                            $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            if ($includeNull) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                    ->where(function($query) {
                                        $query->WhereNull('cases.diagnosis_id');
                                        if (count($this->inputdata->enabledDiagnosis) != 0)
                                        {
                                            $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                        }

                                    });

                            } else {
                                if (count($this->inputdata->enabledDiagnosis) != 0) {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('practice_location_id', $clinicidy)
                                        ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                } else {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('practice_location_id', $clinicidy)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                }
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        }
         
                        //getting total by month
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges[0];
                        $lastDateWanted = $this->inputdata->sortedRanges[0];
                        $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 month'));
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->where('practice_location_id', $clinicidy)
                                ->converted()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $outputdataloc[$a]['total'] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        // getting change
                        $firstreal =  strtotime("-1 month", strtotime($this->inputdata->sortedRanges[0]));
                        $secondreal = strtotime("-1 month", strtotime($this->inputdata->sortedRanges[$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->where('practice_location_id', $clinicidy)
                                ->converted()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
                        
                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        
                        $a = $a + 1;
                    }
                }

                if ((count($this->inputdata->enabledActivity) == 0) && (count($this->inputdata->enabledClinicsMain) == 0) && count($this->inputdata->enabledDiagnosis) == 0)
                {
					$outputdataloc=null;
                }
           
                return View::make('site/report/locationtable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
            }
            if ($this->inputdata->timedivision == 'week')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $templateofyear = array('Location', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Total', '% Change');
                $a = 0;

                if (count($this->inputdata->enabledDiagnosis) != 0) {
                    if (in_array(-1, $this->inputdata->enabledDiagnosis))
                    {
                        unset($this->inputdata->enabledDiagnosis[count($this->inputdata->enabledDiagnosis) - 1]);
                        $includeNull = true;
                    }
                }
                
                if (count($this->inputdata->enabledClinicsMain) != 0)
                {
                    foreach ($this->inputdata->enabledClinicsMain as $clinicidy)
                    {
                        $officeLocation = OfficeLocation::findOrFail($clinicidy);
                        $outputdataloc[$a]['location'] = $officeLocation->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $firstpoint = $this->inputdata->sortedRanges[$i];
                            $lastDateWanted = $this->inputdata->sortedRanges[$i];
                            $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            if ($includeNull) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                    ->where(function($query) {
                                        $query->WhereNull('cases.diagnosis_id');
                                        if (count($this->inputdata->enabledDiagnosis) != 0)
                                        {
                                            $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                        }

                                    });

                            } else {
                                if (count($this->inputdata->enabledDiagnosis) != 0) {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('practice_location_id', $clinicidy)
                                        ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                } else {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('cases.practice_location_id', $clinicidy)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                }
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        }
         
                        //getting total by week
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges[0];
                        $lastDateWanted = $this->inputdata->sortedRanges[0];
                        $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->where('practice_location_id', $clinicidy)
                                ->converted()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $outputdataloc[$a]['total'] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        // getting change
                        $firstreal =  strtotime("-1 week", strtotime($this->inputdata->sortedRanges[0]));
                        $secondreal = strtotime("-1 week", strtotime($this->inputdata->sortedRanges[$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $tmppastyearresult = count(Auth::user()->practice->cases()
                            ->where('practice_location_id', $clinicidy)
                            ->converted()
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray());


                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        
                        $a = $a + 1;
                    }
                }

                if ((count($this->inputdata->enabledActivity) == 0) && (count($this->inputdata->enabledClinicsMain) == 0) && count($this->inputdata->enabledDiagnosis) == 0)
                {
					$outputdataloc=null;
                }
           
                return View::make('site/report/locationtable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
            }
            if ($this->inputdata->timedivision == 'quarter')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByWeeks($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);

                $templateofyear = array('Location');
                for ($i = 1; $i < ($rangeresult + 1); $i++)
                {
                    $templateofyear[] = $i.'th week';
                }
                $templateofyear[] = 'Total';
                $templateofyear[] =  '% Change';
 
                $a = 0;

                if (count($this->inputdata->enabledDiagnosis) != 0) {
                    if (in_array(-1, $this->inputdata->enabledDiagnosis))
                    {
                        unset($this->inputdata->enabledDiagnosis[count($this->inputdata->enabledDiagnosis) - 1]);
                        $includeNull = true;
                    }
                }

                
                if (count($this->inputdata->enabledClinicsMain) != 0)
                {
                    foreach ($this->inputdata->enabledClinicsMain as $clinicidy)
                    {
                        $officeLocation = OfficeLocation::findOrFail($clinicidy);
                        $outputdataloc[$a]['location'] = $officeLocation->name;
                        for ($i = 0; $i < $rangeresult; $i++)
                        { //dataget
                            $firstpoint = $this->inputdata->sortedRanges[$i];
                            $lastDateWanted = $this->inputdata->sortedRanges[$i];
                            $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));
                            $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                            $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            
                            if ($includeNull) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                    ->where(function($query) {
                                        $query->WhereNull('cases.diagnosis_id');
                                        if (count($this->inputdata->enabledDiagnosis) != 0)
                                        {
                                            $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                        }

                                    });

                            } else {
                                if (count($this->inputdata->enabledDiagnosis) != 0) {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('practice_location_id', $clinicidy)
                                        ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                } else {
                                    $yearPatients = Auth::user()->practice->cases()
                                        ->where('practice_location_id', $clinicidy)
                                        ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                        ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                                }
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        }
         
                        //getting total by quarter
                        $things = $rangeresult - 1;
                        $firstpoint = $this->inputdata->sortedRanges[0];
                        $lastDateWanted = $this->inputdata->sortedRanges[0];
                        $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +3 month'));
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->where('practice_location_id', $clinicidy)
                                ->converted()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $outputdataloc[$a]['total'] = count($yearPatients->get(array('cases.created_at'))->toArray());
                        // getting change
                        $firstreal =  strtotime("-3 month", strtotime($this->inputdata->sortedRanges[0]));
                        $secondreal = strtotime("-3 month", strtotime($this->inputdata->sortedRanges[$things]));
                        $firstpoint = date("Y-m-d", $firstreal);
                        $secondpoint = date("Y-m-d", $secondreal);
                        $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                        $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($includeNull) {
                            $yearPatients = Auth::user()->practice->cases()
                                ->where('practice_location_id', $clinicidy)
                                ->converted()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id')
                                        ->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                });

                        } else {
                            if (count($this->inputdata->enabledDiagnosis) != 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->where('practice_location_id', $clinicidy)
                                    ->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis)
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = $officeLocation->patients()
                                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
                        
                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);
                        
                        $a = $a + 1;
                    }
                }

                if ((count($this->inputdata->enabledActivity) == 0) && (count($this->inputdata->enabledClinicsMain) == 0) && count($this->inputdata->enabledDiagnosis) == 0)
                {
					$outputdataloc=null;
                }
           
                return View::make('site/report/locationtable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
                
            } // quarter ends
        } else
        {
            return false;
        }
    }
    public function percent($num_amount, $num_total) {
        if ($num_total == 0)
        {
            return '0';
        }
        return number_format((1 - $num_amount / $num_total) * 100, 2); // yields 0.76
    }
    
    public function doFilters()
    {
        if (count($this->inputdata->enabledClinicsRef) != 0)
            {
                foreach($this->inputdata->enabledClinicsRef as $clinicrefID)
                {
                    foreach($this->inputdata->enabledClinicsMain as $clinicmainKey => $clinicmainIDvalue)
                    { // deleting clinic which added in refsourcefilter from allclinicfilter
                        if ($clinicrefID == $clinicmainIDvalue)
                        {
                            unset($this->inputdata->enabledClinicsMain[$clinicmainKey]);
                        }
                    }
                    foreach($this->inputdata->enabledReferralsRef as $referralkey => $referralvalue)
                    { // deleting doctors id which`s clinic tagged in refsourcefilter
                        $referralchecking = Referralgrid::find($referralvalue);
                        if ($clinicrefID == $referralchecking->practice_location_id)
                        {
                            unset($this->inputdata->enabledReferralsRef[$referralkey]);
                        } 
                    }
                }
                foreach($this->inputdata->enabledReferralsRef as $doctorID)
                {
                    foreach($this->inputdata->enabledClinicsMain as $clinicmainKey => $clinicmainIDvalue)
                    {// deleting clinicallfilterunits for doctors selected in those clinics
                        $referralcheckingfor = Referralgrid::find($doctorID);
                        if ($clinicmainIDvalue == $referralcheckingfor->practice_location_id)
                        {
                            unset($this->inputdata->enabledClinicsMain[$clinicmainKey]);
                        }
                        
                    }
                }
            }
            if (count($this->inputdata->enabledClinicsMain) != 0 )
            { // if referral clinic empty doing this
                if (count($this->inputdata->enabledClinicsRef) == 0)
                {
                    if (count($this->inputdata->enabledReferralsRef) != 0)
                    {
                        foreach($this->inputdata->enabledReferralsRef as $doctorID)
                        {
                            foreach($this->inputdata->enabledClinicsMain as $clinicmainKey => $clinicmainIDvalue)
                            {// deleting clinicallfilterunits for doctors selected in those clinics
                                $referralcheckingfor = Referralgrid::find($doctorID);
                                if ($clinicmainIDvalue == $referralcheckingfor->practice_location_id)
                                {
                                    unset($this->inputdata->enabledClinicsMain[$clinicmainKey]);
                                }
                                
                            }
                        }
                    }
                }
            } 
    }

	private function patientCount($inputdata,$firstpointday,$secondpointday){
		$patientsCount=0;
        $patients = Auth::user()->practice->cases()->converted();
        $includeNull = false;
        if (count($inputdata->enabledClinicsMain) == 0 && count($inputdata->enabledDiagnosis) == 0 && count($inputdata->enabledActivity) == 0)
        {
            return 0;
        }
		if (count($inputdata->enabledClinicsRef) != 0)
		{
			foreach ($inputdata->enabledClinicsRef as $clinicidy)
			{
				$patients->where('created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))->where('created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
			}
		}
		if (count($inputdata->enabledDiagnosis) != 0)
		{
            $temp = array();
            if (in_array(-1, $this->inputdata->enabledDiagnosis))
            {
                $temp = $this->inputdata->enabledDiagnosis;
                unset($temp[count($this->inputdata->enabledDiagnosis) - 1]);
                $includeNull = true;
            }
            if ($includeNull) {
                $patients->where(function($query) use($temp) {
                    $query->WhereNull('cases.diagnosis_id')
                        ->orWhereIn('cases.diagnosis_id', $temp);
                });
            } else {
                $patients->whereIn('cases.diagnosis_id', $inputdata->enabledDiagnosis);
            }

		}
		if (count($inputdata->enabledActivity) != 0)
		{
            $patients->whereIn('cases.activity_id', $inputdata->enabledActivity)->where('is_activities_type', '=',0);
		}
		if (count($inputdata->enabledClinicsMain) != 0)
		{
            $patients->whereIn('practice_location_id', $inputdata->enabledClinicsMain);
		}
		if (count($inputdata->enabledReferralsRef) != 0)
		{
			foreach ($inputdata->enabledReferralsRef as $referralsID)
			{
				$patients = Auth::user()->practice->cases()
                    ->where('cases.referralsource_id', '=', $referralsID)
                    ->converted()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                    ->get(array('cases.created_at'))->toArray();
				$patientsCount = $patientsCount + count($patients);
			}
		}

		return count($patients
            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
            ->get(array('cases.created_at'))->toArray());
	}

    public function postGetchartbyrange()
    { // chart by daterange and filtered by pdf spec
        if (Request::ajax())
        {
            $thisuser = User::find(Auth::id());
            $this->inputdata->startdate = date("Y-m-d", strtotime(Input::get('startdate')));
            $this->inputdata->enddate = date("Y-m-d", strtotime(Input::get('enddate')));
            $this->inputdata->timedivision = Input::get('timedivision');
            $this->inputdata->sortedRanges = '';
            $this->inputdata->enabledClinicsRef = json_decode(Input::get('clinicrefcodes'));
            $this->inputdata->enabledReferralsRef = json_decode(Input::get('referralrefcodes'));
            $this->inputdata->enabledClinicsMain = json_decode(Input::get('maincliniccodes'));
			$this->inputdata->enabledDiagnosis =json_decode(Input::get('diagnosiscodes'));
			$this->inputdata->enabledActivity =json_decode(Input::get('activitycodes'));
            $outputdatareps = array();
            $outputdatareps[0] = array();
            $outputdatareps[0]['label'] = 'New Patients';
            $outputdatareps[0]['data'] = array();
            $this->doFilters();

            if ($this->inputdata->timedivision == 'year')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByMonthes($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges['start_dates']);
                $outputdatareps[0]['label'] = 'Patients in '.date("Y", strtotime(Input::get('startdate'))).' year by months.';

                //picking up results from database
                for ($i = 0; $i < $rangeresult; $i++)
                {
                    $firstpoint = $this->inputdata->sortedRanges['start_dates'][$i];
                    $secondpoint = $this->inputdata->sortedRanges['end_dates'][$i];
                    $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                    $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
					$yearPatientsCount=$this->patientCount($this->inputdata,$firstpointday,$secondpointday);

                    $outputdatareps[0]['data'][] = array(($firstpointday*1000), $yearPatientsCount);
                }
                return Response::json($outputdatareps);

            }
           
            if ($this->inputdata->timedivision == 'quarter')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByWeeks($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $outputdatareps[0]['label'] = 'New Patients in '.date("Y-m", strtotime(Input::get('startdate'))).' - '.date("Y-m", strtotime(Input::get('enddate'))).' quarter by weeks.';
                //picking up results from database
                for ($i = 0; $i < $rangeresult; $i++)
                {
                    $firstpoint = $this->inputdata->sortedRanges[$i];
                    $lastDateWanted = $this->inputdata->sortedRanges[$i];
                    $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));
                    $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                    $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                    $quarterPatientsCount = $this->patientCount($this->inputdata,$firstpointday,$secondpointday);

                    $outputdatareps[0]['data'][] = array(($firstpointday*1000), $quarterPatientsCount);
                }
                return Response::json($outputdatareps);
            }
            
            if ($this->inputdata->timedivision == 'month')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $outputdatareps[0]['label'] = 'New Patients in '.date("Y-m-d", strtotime(Input::get('startdate'))).' - '.date("Y-m-d", strtotime(Input::get('enddate'))).' month by days.';
                for ($i = 0; $i < $rangeresult; $i++)
                {
                    $firstpoint = $this->inputdata->sortedRanges[$i];
                    $lastDateWanted = $this->inputdata->sortedRanges[$i];
                    $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));

                    $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                    $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                    $monthPatientsCount = $this->patientCount($this->inputdata,$firstpointday,$secondpointday);
                   
                    $outputdatareps[0]['data'][] = array(($firstpointday*1000), $monthPatientsCount);
                }
                
                return Response::json($outputdatareps);
            }
            
            if ($this->inputdata->timedivision == 'week')
            {
                $this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
                $rangeresult = count($this->inputdata->sortedRanges);
                $outputdatareps[0]['label'] = 'New Patients in '.date("Y-m-d", strtotime(Input::get('startdate'))).' - '.date("Y-m-d", strtotime(Input::get('enddate'))).' week by days.';
                for ($i = 0; $i < $rangeresult; $i++)
                {
                    $firstpoint = $this->inputdata->sortedRanges[$i];
                    $lastDateWanted = $this->inputdata->sortedRanges[$i];
                    $secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));

                    $firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
                    $secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                    $weekPatientsCount = $this->patientCount($this->inputdata,$firstpointday,$secondpointday);
                 
                    $outputdatareps[0]['data'][] = array(($firstpointday*1000), $weekPatientsCount);
                }
                
                return Response::json($outputdatareps);
            }
        } else
        {
            return false;
        }
    }

	public function postGetdiagnosistable()
	{ // diagnosis table by daterange and filtered by pdf spec
		if (Request::ajax())
		{
			$this->inputdata->startdate = date("Y-m-d", strtotime(Input::get('startdate')));
			$this->inputdata->enddate = date("Y-m-d", strtotime(Input::get('enddate')));
			$this->inputdata->timedivision = Input::get('timedivision');
			$this->inputdata->sortedRanges = '';
			$this->inputdata->enabledClinicsRef = json_decode(Input::get('clinicrefcodes'));
			$this->inputdata->enabledReferralsRef = json_decode(Input::get('referralrefcodes'));
			$this->inputdata->enabledClinicsMain = json_decode(Input::get('maincliniccodes'));
			$this->inputdata->enabledDiagnosis =json_decode(Input::get('diagnosiscodes'));
            $this->inputdata->enabledActivity = json_decode(Input::get('activitycodes'));
			$outputdataloc = array();
			$outputdataloc[0] = array();

			$this->doFilters();


			if ($this->inputdata->timedivision == 'year')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByMonthes($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges['start_dates']);
				$templateofyear = array('Diagnosis', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total', '% Change');
				$a = 0;

				if (count($this->inputdata->enabledDiagnosis) != 0)
				{
					foreach ($this->inputdata->enabledDiagnosis as $clinicidy)
					{
                        if ($clinicidy < 0) {
                            $temppp1[0]['name'] = "No option selected";
                        } else {
                            $temppp1 = Diagnoses::where('id', '=', $clinicidy)->get()->toArray();
                        }
						$outputdataloc[$a]['location'] = $temppp1[0]['name'];
						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges['start_dates'][$i];
							$secondpoint = $this->inputdata->sortedRanges['end_dates'][$i];
							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                            if ($clinicidy < 0) {
                                $yearPatients = Auth::user()->practice->cases()->converted()
                                    ->diagnosisnull()->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()->converted()
                                    ->where('cases.diagnosis_id', '=', $clinicidy)
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }

                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            if (count($this->inputdata->enabledClinicsMain) != 0)
                            {
                                $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                            }

							$outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
						}

						//getting total by year
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges['start_dates'][0];
						$secondpoint = $this->inputdata->sortedRanges['end_dates'][$things];
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }

                        $outputdataloc[$a]['total'] = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
						// getting change
						$firstreal =  strtotime("-1 year", strtotime($this->inputdata->sortedRanges['start_dates'][0]));
						$secondreal = strtotime("-1 year", strtotime($this->inputdata->sortedRanges['end_dates'][$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }
						$tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );

						$outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);

						$a = $a + 1;
					}
				}



				if ((count($this->inputdata->enabledDiagnosis) == 0))
				{

					$outputdataloc=null;

				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics

			} // year ends

			if ($this->inputdata->timedivision == 'month')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges);

				$templateofyear = array('Diagnosis');
				for ($i = 1; $i < ($rangeresult + 1); $i++)
				{
					$templateofyear[] = $i.'th '.date('M', $this->toUnixtimestampFromDateTime($this->inputdata->startdate));
				}
				$templateofyear[] = 'Total';
				$templateofyear[] =  '% Change';

				$a = 0;

				if (count($this->inputdata->enabledDiagnosis) != 0)
				{
					foreach ($this->inputdata->enabledDiagnosis as $clinicidy)
					{
                        if ($clinicidy < 0) {
                            $temppp1[0]['name'] = "No option selected";
                        } else {
                            $temppp1 = Diagnoses::where('id', '=', $clinicidy)->get()->toArray();
                        }
                        $outputdataloc[$a]['location'] = $temppp1[0]['name'];
						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges[$i];
							$lastDateWanted = $this->inputdata->sortedRanges[$i];
							$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));

							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            if ($clinicidy < 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->diagnosisnull()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->where('cases.diagnosis_id', '=', $clinicidy)
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }

                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            if (count($this->inputdata->enabledClinicsMain) != 0)
                            {
                                $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                            }

                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
						}

						//getting total by month
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges[0];
						$lastDateWanted = $this->inputdata->sortedRanges[0];
						$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 month'));

						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }

                        $outputdataloc[$a]['total'] = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
						// getting change
						$firstreal =  strtotime("-1 month", strtotime($this->inputdata->sortedRanges[0]));
						$secondreal = strtotime("-1 month", strtotime($this->inputdata->sortedRanges[$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }
                        $tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );

						$outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);

						$a = $a + 1;
					}
				}


				if ((count($this->inputdata->enabledDiagnosis) == 0))
				{
					$outputdataloc=null;
				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
			}

			if ($this->inputdata->timedivision == 'week')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges);
				$templateofyear = array('Diagnosis', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Total', '% Change');
				$a = 0;

				if (count($this->inputdata->enabledDiagnosis) != 0)
				{
					foreach ($this->inputdata->enabledDiagnosis as $clinicidy)
					{
                        if ($clinicidy < 0) {
                            $temppp1[0]['name'] = "No option selected";
                        } else {
                            $temppp1 = Diagnoses::where('id', '=', $clinicidy)->get()->toArray();
                        }
                        $outputdataloc[$a]['location'] = $temppp1[0]['name'];
						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges[$i];
							$lastDateWanted = $this->inputdata->sortedRanges[$i];
							$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));

							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            if ($clinicidy < 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->diagnosisnull()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->where('cases.diagnosis_id', '=', $clinicidy)
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }

                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            if (count($this->inputdata->enabledClinicsMain) != 0)
                            {
                                $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                            }

                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
						}

						//getting total by week
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges[0];
						$lastDateWanted = $this->inputdata->sortedRanges[0];
						$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));

						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }

                        $outputdataloc[$a]['total'] = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
						// getting change
						$firstreal =  strtotime("-1 week", strtotime($this->inputdata->sortedRanges[0]));
						$secondreal = strtotime("-1 week", strtotime($this->inputdata->sortedRanges[$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }
                        $tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );

						$outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);

						$a = $a + 1;
					}
				}

				if ((count($this->inputdata->enabledDiagnosis) == 0))
				{
					$outputdataloc=null;
				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
			}

			if ($this->inputdata->timedivision == 'quarter')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByWeeks($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges);

				$templateofyear = array('Diagnosis');
				for ($i = 1; $i < ($rangeresult + 1); $i++)
				{
					$templateofyear[] = $i.'th week';
				}
				$templateofyear[] = 'Total';
				$templateofyear[] =  '% Change';

				$a = 0;

				if (count($this->inputdata->enabledDiagnosis) != 0)
				{
					foreach ($this->inputdata->enabledDiagnosis as $clinicidy)
					{
                        if ($clinicidy < 0) {
                            $temppp1[0]['name'] = "No option selected";
                        } else {
                            $temppp1 = Diagnoses::where('id', '=', $clinicidy)->get()->toArray();
                        }
                        $outputdataloc[$a]['location'] = $temppp1[0]['name'];
						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges[$i];
							$lastDateWanted = $this->inputdata->sortedRanges[$i];
							$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));

							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            if ($clinicidy < 0) {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->diagnosisnull()
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            } else {
                                $yearPatients = Auth::user()->practice->cases()
                                    ->converted()
                                    ->where('cases.diagnosis_id', '=', $clinicidy)
                                    ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                    ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                            }

                            if (count($this->inputdata->enabledActivity) != 0) {
                                $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            if (count($this->inputdata->enabledClinicsMain) != 0)
                            {
                                $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                            }

                            $outputdataloc[$a]['data'][$i] = count($yearPatients->get(array('cases.created_at'))->toArray());
						}

						//getting total by quarter
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges[0];
						$lastDateWanted = $this->inputdata->sortedRanges[0];
						$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +3 month'));

						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }

                        $outputdataloc[$a]['total'] = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );
						// getting change
						$firstreal =  strtotime("-3 month", strtotime($this->inputdata->sortedRanges[0]));
						$secondreal = strtotime("-3 month", strtotime($this->inputdata->sortedRanges[$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        if ($clinicidy < 0)
                        {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->diagnosisnull()
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        } else {
                            $yearPatients = Auth::user()->practice->cases()
                                ->converted()
                                ->where('cases.diagnosis_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday));
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $yearPatients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        if (count($this->inputdata->enabledClinicsMain) != 0)
                        {
                            $yearPatients->whereIn('practice_location_id',$this->inputdata->enabledClinicsMain);
                        }
                        $tmppastyearresult = count(
                            $yearPatients->get(array('cases.created_at'))->toArray()
                        );

						$outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);

						$a = $a + 1;
					}
				}

				if ((count($this->inputdata->enabledDiagnosis) == 0))
				{
					$outputdataloc=null;
				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics

			} // quarter ends
		} else
		{
			return false;
		}
	}

	public function postGetactivitytable()
	{ // activity table by daterange and filtered by pdf spec
		if (Request::ajax())
		{
			$this->inputdata->startdate = date("Y-m-d", strtotime(Input::get('startdate')));
			$this->inputdata->enddate = date("Y-m-d", strtotime(Input::get('enddate')));
			$this->inputdata->timedivision = Input::get('timedivision');
			$this->inputdata->sortedRanges = '';
			$this->inputdata->enabledClinicsRef = json_decode(Input::get('clinicrefcodes'));
			$this->inputdata->enabledReferralsRef = json_decode(Input::get('referralrefcodes'));
			$this->inputdata->enabledClinicsMain = json_decode(Input::get('maincliniccodes'));
			$this->inputdata->enabledDiagnosis =json_decode(Input::get('diagnosiscodes'));
			$this->inputdata->enabledActivity =json_decode(Input::get('activitycodes'));
			$outputdataloc = array();
			$outputdataloc[0] = array();
            $includeNull = false;

			$this->doFilters();
            if (count($this->inputdata->enabledDiagnosis) != 0) {
                if (in_array(-1, $this->inputdata->enabledDiagnosis))
                {
                    unset($this->inputdata->enabledDiagnosis[count($this->inputdata->enabledDiagnosis) - 1]);
                    $includeNull = true;
                }
            }

			if ($this->inputdata->timedivision == 'year')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByMonthes($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges['start_dates']);
				$templateofyear = array('Marketing Activity', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total', '% Change');
				$a = 0;

				if (count($this->inputdata->enabledActivity) != 0)
				{

					foreach ($this->inputdata->enabledActivity as $clinicidy)
					{
						$activities = Activities::findOrFail($clinicidy);
						$outputdataloc[$a]['location'] = $activities->campaign_name;

						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges['start_dates'][$i];
							$secondpoint = $this->inputdata->sortedRanges['end_dates'][$i];
							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
							$yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();

							$outputdataloc[$a]['data'][$i] = count($yearPatients);
						}

						//getting total by year
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges['start_dates'][0];
						$secondpoint = $this->inputdata->sortedRanges['end_dates'][$things];
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $outputdataloc[$a]['total'] = count($yearPatients);
						// getting change
						$firstreal =  strtotime("-1 year", strtotime($this->inputdata->sortedRanges['start_dates'][0]));
						$secondreal = strtotime("-1 year", strtotime($this->inputdata->sortedRanges['end_dates'][$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
						$tmppastyearresult = count($yearPatients);

						$outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);


						$a = $a + 1;
					}
				}



				if ((count($this->inputdata->enabledActivity) == 0))
				{
					$outputdataloc=null;
				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics

			} // year ends

			if ($this->inputdata->timedivision == 'month')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges);

				$templateofyear = array('Marketing Activity');
				for ($i = 1; $i < ($rangeresult + 1); $i++)
				{
					$templateofyear[] = $i.'th '.date('M', $this->toUnixtimestampFromDateTime($this->inputdata->startdate));
				}
				$templateofyear[] = 'Total';
				$templateofyear[] =  'Change %';

				$a = 0;

				if (count($this->inputdata->enabledActivity) != 0)
				{
					foreach ($this->inputdata->enabledActivity as $clinicidy)
					{
						$temppp1 = Activities::where('id','=',$clinicidy)->get()->toArray();
						if(!$temppp1) {
							$temppp1 = ActivityTypes::where('id', '=', $clinicidy)->get()->toArray();
							$field_name='name';
							$is_activities_type=1;
						}else{
							$field_name='campaign_name';
							$is_activities_type=0;
						}
						$outputdataloc[$a]['location'] = $temppp1[0][$field_name];

						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges[$i];
							$lastDateWanted = $this->inputdata->sortedRanges[$i];
							$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));

							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();

                            $outputdataloc[$a]['data'][$i] = count($yearPatients);
						}

						//getting total by month
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges[0];
						$lastDateWanted = $this->inputdata->sortedRanges[0];
						$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 month'));

						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $outputdataloc[$a]['total'] = count($yearPatients);
						// getting change
						$firstreal =  strtotime("-1 month", strtotime($this->inputdata->sortedRanges[0]));
						$secondreal = strtotime("-1 month", strtotime($this->inputdata->sortedRanges[$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $tmppastyearresult = count($yearPatients);

                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);


						$a = $a + 1;
                    }
				}
				if ((count($this->inputdata->enabledActivity) == 0))
				{
					$outputdataloc=null;

				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
			}

			if ($this->inputdata->timedivision == 'week')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByDays($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges);
				$templateofyear = array('Marketing Activity', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Total', 'Change %');
				$a = 0;

				if (count($this->inputdata->enabledActivity) != 0)
				{
					foreach ($this->inputdata->enabledActivity as $clinicidy)
					{
						$temppp1 = Activities::where('id','=',$clinicidy)->get()->toArray();
						if(!$temppp1) {
							$temppp1 = ActivityTypes::where('id', '=', $clinicidy)->get()->toArray();
							$field_name='name';
							$is_activities_type=1;
						}else{
							$field_name='campaign_name';
							$is_activities_type=0;
						}
						$outputdataloc[$a]['location'] = $temppp1[0][$field_name];

						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges[$i];
							$lastDateWanted = $this->inputdata->sortedRanges[$i];
							$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 day'));

							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();

                            $outputdataloc[$a]['data'][$i] = count($yearPatients);
						}

						//getting total by week
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges[0];
						$lastDateWanted = $this->inputdata->sortedRanges[0];
						$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));

						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $outputdataloc[$a]['total'] = count($yearPatients);
						// getting change
						$firstreal =  strtotime("-1 week", strtotime($this->inputdata->sortedRanges[0]));
						$secondreal = strtotime("-1 week", strtotime($this->inputdata->sortedRanges[$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $tmppastyearresult = count($yearPatients);

                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);

						$a = $a + 1;
					}
				}

				if ((count($this->inputdata->enabledActivity) == 0))
				{
					$outputdataloc=null;
				}

				return View::make('site/report/diagnosistable')->with('data1', $outputdataloc)->with('headers', $templateofyear); // send data for year for clinics
			}

			if ($this->inputdata->timedivision == 'quarter')
			{
				$this->inputdata->sortedRanges = $this->dateRangeSorterByWeeks($this->inputdata->startdate, $this->inputdata->enddate);
				$rangeresult = count($this->inputdata->sortedRanges);

				$templateofyear = array('Marketing Activity');
				for ($i = 1; $i < ($rangeresult + 1); $i++)
				{
					$templateofyear[] = $i.'th week';
				}
				$templateofyear[] = 'Total';
				$templateofyear[] =  'Change %';

				$a = 0;

				if (count($this->inputdata->enabledActivity) != 0)
				{
					foreach ($this->inputdata->enabledActivity as $clinicidy)
					{
						$temppp1 = Activities::where('id','=',$clinicidy)->get()->toArray();
						if(!$temppp1) {
							$temppp1 = ActivityTypes::where('id', '=', $clinicidy)->get()->toArray();
							$field_name='name';
							$is_activities_type=1;
						}else{
							$field_name='campaign_name';
							$is_activities_type=0;
						}
						$outputdataloc[$a]['location'] = $temppp1[0][$field_name];

						for ($i = 0; $i < $rangeresult; $i++)
						{ //dataget
							$firstpoint = $this->inputdata->sortedRanges[$i];
							$lastDateWanted = $this->inputdata->sortedRanges[$i];
							$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +1 week'));

							$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
							$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);

                            $patients = Auth::user()->practice->cases()->converted();
                            if (count($this->inputdata->enabledClinicsMain) != 0) {
                                $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                            }
                            if ($includeNull) {
                                $patients->where(function($query) {
                                    $query->WhereNull('cases.diagnosis_id');
                                    if (count($this->inputdata->enabledDiagnosis) != 0)
                                    {
                                        $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                    }
                                });
                            } else {
                                $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                            }
                            if (count($this->inputdata->enabledActivity) != 0) {
                                $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                            }
                            $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                                ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                                ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                                ->get(array('cases.created_at'))->toArray();

                            $outputdataloc[$a]['data'][$i] = count($yearPatients);
						}

						//getting total by quarter
						$things = $rangeresult - 1;
						$firstpoint = $this->inputdata->sortedRanges[0];
						$lastDateWanted = $this->inputdata->sortedRanges[0];
						$secondpoint = date('Y-m-d', strtotime($lastDateWanted .' +3 month'));

						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $outputdataloc[$a]['total'] = count($yearPatients);
						// getting change
						$firstreal =  strtotime("-3 month", strtotime($this->inputdata->sortedRanges[0]));
						$secondreal = strtotime("-3 month", strtotime($this->inputdata->sortedRanges[$things]));
						$firstpoint = date("Y-m-d", $firstreal);
						$secondpoint = date("Y-m-d", $secondreal);
						$firstpointday = $this->toUnixtimestampFromDateTime($firstpoint);
						$secondpointday = $this->toUnixtimestampFromDateTime($secondpoint);
                        $patients = Auth::user()->practice->cases()->converted();
                        if (count($this->inputdata->enabledClinicsMain) != 0) {
                            $patients->whereIn('practice_location_id', $this->inputdata->enabledClinicsMain);
                        }
                        if ($includeNull) {
                            $patients->where(function($query) {
                                $query->WhereNull('cases.diagnosis_id');
                                if (count($this->inputdata->enabledDiagnosis) != 0)
                                {
                                    $query->orWhereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                                }
                            });
                        } else {
                            $patients->whereIn('cases.diagnosis_id', $this->inputdata->enabledDiagnosis);
                        }
                        if (count($this->inputdata->enabledActivity) != 0) {
                            $patients->whereIn('cases.activity_id',$this->inputdata->enabledActivity);
                        }
                        $yearPatients = $patients->where('cases.activity_id', '=', $clinicidy)
                            ->where('cases.created_at', '>=', $this->toDateTimeFromUnixtimestamp($firstpointday))
                            ->where('cases.created_at', '<=', $this->toDateTimeFromUnixtimestamp($secondpointday))
                            ->get(array('cases.created_at'))->toArray();
                        $tmppastyearresult = count($yearPatients);

                        $outputdataloc[$a]['change'] = $this->percent($tmppastyearresult, $outputdataloc[$a]['total']);

						$a = $a + 1;
					}
				}

				if ((count($this->inputdata->enabledActivity) == 0))
				{
					$outputdataloc=null;
				}
			return View::make('site/report/activitytable')->with('data1', $outputdataloc)->with('headers', $templateofyear);
		} else
		{
			return false;
		}
	}

}
}
