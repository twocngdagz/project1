<?php
use LaravelBook\Ardent\Ardent;

class Diagnoses extends Eloquent
{
	//~  table of model
	protected $table = 'diagnosis';

    public function patients()
    {
        return $this->hasMany('Patient');
    }


}
