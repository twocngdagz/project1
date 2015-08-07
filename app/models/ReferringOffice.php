<?php

class ReferringOffice extends Eloquent {

    protected $table = 'referring_offices';
    protected $guarded = array('id');

    public function referralSources()
    {
        return $this->hasMany('ReferralSource', 'referring_office_id');
    }

    public function practice()
    {
        return $this->belongsTo('Practice', 'practice_id');
    }

    public function tasks()
    {
        return $this->hasMany('Crmtasks');
    }

    public function notes()
    {
        return $this->hasMany('Crmnotes');
    }

} 