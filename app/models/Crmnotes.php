<?php

use LaravelBook\Ardent\Ardent;

class Crmnotes extends Ardent
{
    //~  table of model
    protected $table = 'crm_notes';
    protected $guarded = array('id');
    public static $rules = array(
      'description' => 'required'
    );

    public static $relationsData = array(
        'referringOffice' => array(self::BELONGS_TO, 'ReferringOffice', 'foreignKey' => 'referring_office_id'),
        'user' => array(self::BELONGS_TO, 'User', 'foreignKey' => 'owner_id')
    );
    
}
