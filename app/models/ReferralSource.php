<?php

class ReferralSource extends Eloquent
{
    //~  table of model
    protected $table = 'referral_source';
    protected $guarded = array('id');

	public function officeLocation()
    {
        return $this->belongsTo('OfficeLocation', 'practice_location_id');
    }

    public function patients()
    {
        return $this->hasMany('Patient');
    }

    public function referralOffice()
    {
        return $this->belongsTo('ReferringOffice', 'referring_office_id');
    }

    public function cases()
    {
        return $this->hasMany('Cases', 'referralsource_id');
    }
}
