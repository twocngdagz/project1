<?php

class Practice extends Eloquent
{
    //~  table of model
    protected $table = 'practice';
    protected $guarded = array('id');


    public function users()
    {
        return $this->hasMany('User');
    }

    public function officeLocations()
    {
        return $this->hasMany('OfficeLocation');
    }

    public function referralSources()
    {
        return $this->hasManyThrough('ReferralSource', 'ReferringOffice', 'practice_id', 'referring_office_id');
    }

    public function patients()
    {
        return $this->hasMany('Patient');
    }

    public function cases()
    {
        return $this->hasManyThrough('Cases', 'Patient');
    }

    public function activities()
    {
        return $this->hasMany('Activities');
    }

    public function insurances()
    {
        return $this->hasMany('Insurance');
    }

    public function therapists()
    {
        return $this->hasMany('Therapists');
    }

    public function referringOffices()
    {
        return $this->hasMany('ReferringOffice', 'practice_id');
    }

    public function activityTypes()
    {
        return $this->hasMany('ActivityTypes', 'practice_id');
    }

//    public function activityTypes()
//    {
//        if (!$this->activities->isEmpty())
//        {
//            return ActivityTypes::whereIn('id', $this->activities->lists('activity_type_id'))->get()->sortBy('name');
//        } else {
//            return null;
//        }
//    }

    public function reasons()
    {
        return ReasonNotScheduled::where(function($query)
        {
            $query->where('practice_id', $this->id);
        })->get();
    }

    public function diagnosis($id=null)
    {
        if (Request::is('admin/*')) {
            return Diagnoses::where(function($query) use ($id)
            {
                $query->where('company_id', $id);
            })->get();
        } else {
            return Diagnoses::where(function($query)
            {
                $query->where('company_id', $this->id);
            })->get();
        }

    }

    public function hasActivity($name)
    {
        foreach ($this->activities as $activity) {
            if ($activity->campaign_name == $name)
            {
                return true;
            }
        }
        return false;
    }
}
