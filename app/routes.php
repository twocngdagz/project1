<?php
//blade addition
Blade::extend(function($view, $compiler)
{
    $pattern = $compiler->createMatcher('m_format');
    return preg_replace($pattern, '$1<?php echo number_format($2, 2, ".", ","); ?>', $view);
});


//~  Route model binding 
Route::model('user', 'User');
Route::model('patient', 'Patient');
Route::model('clinic', 'Clinic');
Route::model('crmnotes', 'Crmnotes');
Route::model('activities', 'Activities');
Route::model('activitytype', 'ActivityType');
//~ Route::model('crmtasks', 'Crmtasks');
Route::model('insurance', 'Insurance');
Route::model('referralgrid', 'Referralgrid');
Route::model('cliniclocation', 'Cliniclocation');

//~ Route::model('report', 'Report');


//~  Route constraint patterns
Route::pattern('user', '[0-9]+');

//~  Admin Routes

//~  ***** Frontend Routes *****
//~ User Account Route login/register/confirm gets, posts are restful
Route::get('user/login', function() { return View::make('site/layouts/login'); });
Route::get('user/register', function() { return View::make('site/layouts/register'); });


//all Routes need with only auth
Route::group(array('before' => 'auth'), function()
{
    // default views
    Route::get('user/dashboard', function() { return View::make('site/dashboard/dashboard'); });
    Route::get('user/patient','PatientController@getPatientpage' );
    Route::get('user/patient/id/{token}', ['as' => 'patients-edit','uses' => 'PatientController@getPatientID']);
    Route::get('patient/patientsnameslist/{token}', 'PatientController@PatientsNamesList');
	Route::get('clinic/clinicnameslist/{token}', 'ClinicController@ClinicNamesList');
	Route::get('clinic/details/id/{id}', 'ClinicController@getDetails');
    //~ Route::get('user/patient', 'PatientController@getPatients');
    Route::get('user/referralgrid', function() { return View::make('site/referralgrid/referralgrid'); });
    Route::get('user/clinic', 'ClinicController@getIndex');
    Route::get('clinic/editclinic/{id}','ClinicController@getEditclinic');
    Route::get('clinic/editdoctor/{id}','ClinicController@getEditdoctor');
    Route::get('user/activity', function() { return View::make('site/activity/activity')->with('activities', Auth::user()->practice->activities->lists('campaign_name')); });
    Route::get('user/report', 'ReportController@getReport');
	Route::get('user', 'UserController@profile');
	Route::get('clinic/updateclinic', 'ClinicController@Updateclinic');
    Route::get('clinic/updatedoctor', 'ClinicController@Updatedoctor');
	Route::get('user/editprofile', array('as' => 'edit-profile', 'uses' => 'UserController@editProfile'));
    Route::post('user/patient/cases',        'CaseController@getCases');
    Route::post('user/patient/cases/add',   'CaseController@addCase');
    Route::post('patient/case/update',      'CaseController@updateCase');
    Route::get('user/case/id/{id}', 'CaseController@getCase');
    Route::post('user/patient/checkin/evaluation',  'CaseController@checkinEvaluation');
    Route::post('user/patient/checkin/appointment',  'CaseController@checkinAppointment');

    Route::group(array('before' => 'isAdmin'), function() {
        Route::any('admin/accountcreation', 'AdminController@postAccountCreation');
        Route::get('admin/practice', 'AdminController@getPractice');
        Route::post('admin/diagnosis/save', 'AdminController@saveDiagnosis');
        Route::post('admin/insurance/save', 'AdminController@saveInsurance');
        Route::post('admin/reason/save', 'AdminController@saveReason');
        Route::get('admin/insurance/{practice_id}', 'AdminController@editInsurance')->where('practice_id','\d+');
        Route::get('admin/diagnosis/{practice_id}', 'AdminController@editDiagnosis')->where('practice_id','\d+');
        Route::get('admin/reason/{practice_id}', 'AdminController@editReason')->where('practice_id','\d+');
        Route::get('admin/diagnosis/delete', 'AdminController@deleteDiagnosis');
        Route::get('admin/insurance/delete', 'AdminController@deleteInsurance');
        Route::get('admin/reason/delete', 'AdminController@deleteReason');
        Route::get('admin/location/{practice_id}', 'AdminController@editLocation')->where('practice_id', '\d+');
        Route::post('admin/location/save', 'AdminController@saveLocation');
        Route::get('admin/location/delete', 'AdminController@deleteLocation');

        Route::get('admin/users/{practice_id}', 'AdminController@showUsers')->where('practice_id','\d+');
        Route::get('admin/user/edit/{id}', 'AdminController@editUser')->where('id', '\d+');
        Route::get('admin/user/create/{practice_id}',    'AdminController@createUser')->where('practice_id','\d+');
        Route::post('admin/user/update',   'AdminController@updateUser');
        Route::post('admin/user/store',    'AdminController@storeUser');
        Route::get('admin/user/delete',    'AdminController@removeUser');
        Route::post('admin/import/referral-source','AdminController@importReferralSource');
    });

	Route::group(array('before' => 'csrf'), function() {

		Route::post('user/editprofile', array('as' => 'post-edit-profile', 'uses' => 'UserController@editProfile'));

	});

    //~  restfull for insite additions
    Route::controller('patient', 'PatientController');
    Route::controller('referralgrid', 'ReferralgridController');
    Route::controller('clinic', 'ClinicController');
    Route::controller('insurance', 'InsuranceController');
    Route::controller('activity', 'ActivityController');
    Route::controller('report', 'ReportController');

	Route::controller('admin', 'AdminController');
});

//~ user index
Route::get('/', function() { return Auth::check() ? Redirect::to('user/patient') : Redirect::to('user/login'); });

//~ User RESTful Routes (Login, Logout, Register, etc)
Route::controller('user', 'UserController');
Route::controller('password', 'RemindersController');
Route::controller('dashboard', 'DashboardController');




/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

