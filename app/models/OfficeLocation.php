<?php

class OfficeLocation extends Eloquent
{
    //~  table of model
    protected $table = 'practice_locations';
    protected $guarded = array('id');


    public function practice()
    {
        return $this->belongsTo('Practice', 'practice_id');
    }

    public function referralSource()
    {
        return $this->hasMany('ReferralSource', 'practice_location_id');
    }

    public function patients()
    {
        return $this->hasMany('Patient', 'practice_location_id');
    }


    
}
