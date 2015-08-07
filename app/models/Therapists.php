<?php

use LaravelBook\Ardent\Ardent;

class Therapists extends Eloquent
{
    //~  table of model
    protected $table = 'therapists';
    protected $guarded = array('id');

    public function practice()
    {
        return $this->belongsTo('Practice', 'practice_id');
    }
    
}
