<?php
use LaravelBook\Ardent\Ardent;

class Patient extends Eloquent
{
    //~  table of model
    protected $table = 'patient';

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    public function scopeIsScheduled($query)
    {
        return $query->where('is_scheduled', 1);
    }

    public function scopeDiagnosisNull($query)
    {
        return $query->WhereNull('diagnosis_id');
    }

    public function reason()
    {
        return $this->belongsTo('ReasonNotScheduled', 'reasonnotscheduled_id');
    }

    public function practice()
    {
        return $this->belongsTo('Practice', 'practice_id');
    }

    public function referralSource()
    {
        return $this->belongsTo('ReferralSource', 'referral_source_id');
    }

    public function activity()
    {
        return $this->belongsTo('Activities', 'activity_id');
    }

    public function officeLocation()
    {
        return $this->belongsTo('OfficeLocation', 'practice_location_id');
    }

    public function insurance()
    {
        return $this->belongsTo('Insurance', 'insurance_id');
    }

    public function therapist()
    {
        return $this->belongsTo('Therapists', 'therapist_id');
    }

    public function how_found()
    {
        return $this->belongsTo('Howfound', 'how_found_id');
    }

    public function diagnosis()
    {
        return $this->belongsTo('Diagnoses', 'diagnosis_id');
    }

    public function scopeConverted($query){
        return $query->whereNotNull('first_appointment');
    }

    public function cases()
    {
        return $this->hasMany('Cases');
    }

  
}
