<?php

class ActivityTypes extends Eloquent
{
    //~  table of model
    protected $table = 'activity_type';

    protected $guarded = array('id');

	public function activities()
	{
		return $this->hasMany('Activities', 'activity_type_id');
	}

    public function practice()
    {
        return $this->belongsTo('Practice');
    }
    
  
}
