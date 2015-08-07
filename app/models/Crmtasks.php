<?php

use LaravelBook\Ardent\Ardent;

class Crmtasks extends Ardent
{
    //~  table of model
    protected $table = 'crm_tasks';
    protected $guarded = array('id');
    public static $rules = array(
      'title' => 'required'
    );

    public static $relationsData = array(
        'referringOffice' => array(self::BELONGS_TO, 'ReferringOffice', 'foreignKey' => 'referring_office_id'),
        'user' => array(self::BELONGS_TO, 'User', 'foreignKey' => 'owner_id')
    );
    
}
