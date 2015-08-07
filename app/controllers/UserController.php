<?php

class UserController extends BaseController {
    
    /**
     * User Model
     * @var User
     */
    protected $user;
    // Injecting models
    // @param User $user
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }
    
    // confirmation account
    public function getConfirm($code)
    {
        $response = $this->user->findTokenToggleAndConfirm($code);
        if ($response != null)
        {
            return Redirect::to('/user/login')->with('info', 'Thank you! Your registration complete! Now you can log in to the system');
        } else
        {
            return Redirect::to('/')->with('error', 'Such code doesn`t exist! Try new registration!');
        }
    }
    
    // login
    public function postLogin()
    {
        //~ getting vars
        $email =  trim(Input::get( 'email' ));
        $password = trim(Input::get( 'password'));
        $remember = Input::get( 'remember' );
        $validatoring = Validator::make(
            array('email' => $email,
                     'password' => $password
            ),
            array('email' => 'required|email',
                    'password' => 'required|min:4'
            )
        );

		Session::put('login_previous_page', '/user/patient');

		if($_SERVER["HTTP_REFERER"] != URL::to('/').'/user/login'){
			Session::put('login_previous_page', $_SERVER["HTTP_REFERER"]);
		}

		if ($validatoring->fails())
        {
            return Redirect::to('/user/login')->withInput(Input::except('password'))->with('error', 'Wrong input, please input email and password!');
        } elseif(!$this->user->isConfirmedAndActive($email))
        {
            return Redirect::to('/user/login')->withInput(Input::except('password'))->with( 'error', 'This user not confirmed yet or not active or even not registered!' );
        } elseif (Auth::attempt(array('email' => $email, 'password' => $password), $remember))
        {
            return Redirect::intended(Session::get('login_previous_page'));
        } else
        {
            return Redirect::to('/user/login')->withInput(Input::except('password'))->with( 'error', 'Wrong email or password!' );
        }
        
    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('/');
    }
    
    public function postRegister()
    {
        $input = new User;
        $input->name = Input::get('name');
        $input->email = Input::get('email');
        $input->password = Input::get('password');
        $input->password_confirmation = Input::get('password_confirmation');
        
        
        //validating and sending email with confirmation code
        if ($input->validate())
        {
            $input->password = Hash::make($input->password);
            $input->confirmed = false;
            $input->role = 'manager';
            // hardcoding for now default columns
            $input->columns_patient = '{"data":"{\"patientclm\":\"true\",\"dateinitiatedclm\":\"false\",\"findusclm\":\"true\",\"insuranceclm\":\"false\",\"isscheduled\":\"true\",\"showedupclm\":\"true\",\"reasonclm\":\"true\",\"referralclm\":\"true\",\"diagnosisclm\":\"true\",\"clinicclm\":\"false\",\"phoneclm\":\"false\"}","is_array":true}';
            $input->filters_patient = ' ';
            $input->confirmation_code = Str::random(32);
            $data = array('code' => $input->confirmation_code);
            Mail::send('emails.confirmation.confirmmail',  $data, function($message) use ($input)
            {
                $message->to($input->email, $input->name)->subject('Completion of registration!');
            });
           $input->forceSave();
            return Redirect::to('/')->with('info', 'Thank you, registration almost complete! Please, wait for email with confirmation code!');
        } else
        {
            return Redirect::to('/user/register')->with('error', $input->errors()->all());
        }
    }

	public function profile(){
		$user = User::find(Auth::id());

		return View::make('site.user.profile', array('user' => $user));
	}

	public function editProfile(){
		if(isset($_POST['EditProfile'])){
			$validator = Validator::make(Input::all(),
				array(
					'old_password'              => 'required',
					'new_password'              => 'required|min:6',
					'password_confirmation' 	=> 'required|same:new_password'
				)
			);

			if($validator->fails()){
				return Redirect::route('edit-profile')->withErrors($validator);
			}else{
				$user=User::find(Auth::id());
				$new_password=Input::get('new_password');
				$old_password=Input::get('old_password');

				if(Hash::check($old_password, $user->getAuthPassword())){
					$user->password=Hash::make($new_password);
					if($user->forceSave()){
						return Redirect::to('/user')->with('info', 'Your profile has been changed.');
					}
				}else{
					return Redirect::route('edit-profile')->with('info', 'Your old password is incorrect.');
				}
			}
		}else{
			return View::make('site.user.editprofile');
		}
	}

    
}

