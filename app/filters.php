<?php



App::before(function($request)
{
    //
});


App::after(function($request, $response)
{
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/


Route::filter('auth', function()
{
    if ( Auth::guest() ) // If the user is not logged in
    {
            return Redirect::guest('user/login');
    }
});

Route::filter('auth.basic', function()
{
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('/user/login/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    if (Session::getToken() != Input::get('csrf_token') &&  Session::getToken() != Input::get('_token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

Route::filter('isAdmin', function()
{
    if (Auth::check())
    {
        if(Auth::user()->role != 'admin')
        {
            Auth::logout();
            return Redirect::guest('user/login');
        }
    } else {
        return Redirect::guest('user/login');
    }

});
