<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use LaravelBook\Ardent\Ardent;

class User extends Ardent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    public static $relationsData = array(
        'practice' => array(self::BELONGS_TO, 'Practice', 'foreignKey' => 'practice_id')
    );

    protected $table = 'users';

    //~ The attributes excluded from the model's JSON form.
    //~ @var array
    protected $hidden = array('password', 'remember_token');
    
    //~ Next protections
    //~ @var array
    //~ protected $fillable = array('name', 'email');
    protected $guarded = array('id', 'password');
    
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
      return $this->getKey();
    }
     
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
      return $this->password;
    }
     
    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
      return $this->email;
    }
    
    public function getName()
    {
        return $this->name;
    }
    // confirmation or die
    // @return confirmed or null
    public function findTokenToggleAndConfirm($token)
    {
        $user1 = $this->where('confirmation_code', $token)->first();
        if (!empty($user1->id))
        {
            $user1->confirmation_code = '';
            $user1->confirmed = true;
            $user1->forceSave();
            return 'Confirmed!';
        } else
        {
            return null;
        }
    }
    // test for confirmation by email 
    public function isConfirmedAndActive($email)
    {
        $results = $this->where('email', $email)->first();
        if (!isset($results->id))
        {
            return false;
        } elseif ($results->confirmed)
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function isValidatedToPractice($practice_id)
    {
      if ($this->practice->id == $practice_id)
      {
        return true;
      }
      return false;
    }
    
   
    //~ Validation rules (Ardent)
    public static $rules = array(
      'name' => 'required|between:4,25',
      'email' => 'required|email',
      'password' => 'required|alpha_num|min:4|confirmed',
      'password_confirmation' => 'required|alpha_num|min:4',
    );
    //~  Auto Purge confirmation
    public $autoPurgeRedundantAttributes = true;
    
}
