<?php


class ReasonNotScheduled extends Eloquent {

    protected $guarded = array('id');
    protected $table = 'reasons';

    public function patients()
    {
        return $this->hasMany('patient');
    }

} 