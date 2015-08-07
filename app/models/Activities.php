<?php

class Activities extends Eloquent
{
    //~  table of model
    protected $table = 'activity';
    protected $guarded = array('id');

    public function activity_type()
    {
        return $this->belongsTo('ActivityTypes', 'activity_type_id');
    }

    public function practice()
    {
        return $this->belongsTo('Practice');
    }
	
}
