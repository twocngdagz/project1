<?php

class Cases extends Eloquent
{
    use SoftDeletingTrait;
    //~  table of model
    protected $table = 'cases';
    protected $guarded = array('id');
    protected $dates = ['deleted_at'];

    public function activity()
    {
        return $this->belongsTo('Activities');
    }

    public function diagnosis()
    {
        return $this->belongsTo('Diagnoses');
    }

    public function patient()
    {
        return $this->belongsTo('Patient');
    }

    public function referralSource()
    {
        return $this->belongsTo('ReferralSource', 'referralsource_id');
    }

    public function scopeIsScheduled($query)
    {
        return $query->where('cases.is_scheduled', 1);
    }

    public function scopeDiagnosisNull($query)
    {
        return $query->WhereNull('cases.diagnosis_id');
    }

    public function scopeConverted($query){
        return $query->whereNotNull('first_appointment');
    }

    public function getDates(){
        $res=parent::getDates();
        array_push($res,"free_evaluation");
        array_push($res,"first_appointment");
        return $res;
    }

}