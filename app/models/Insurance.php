<?php

use LaravelBook\Ardent\Ardent;

class Insurance extends Eloquent
{
    //~  table of model
    protected $table = 'insurance';
    protected $guarded = array('id');

    public function practice()
    {
        return $this->belongsTo('Practice', 'practice_id');
    }
    
}
