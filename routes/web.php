<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/
// Route::get('/', 'TicketsController@index');
Route::get('/about', 'PagesController@about');
Route::get('/contact', 'PagesController@contact');
Route::post('/contact', 'PagesController@submitcontact');
Route::get('/ticket/create', 'TicketsController@create')->middleware('auth');
Route::post('/ticket', 'TicketsController@store')->middleware('auth');
Route::get('/tickets', 'TicketsController@index');
Route::get('/ticket/{slug?}', 'TicketsController@show');
Route::get('/ticket/{slug?}/edit','TicketsController@edit');
Route::post('/ticket/{slug?}/edit','TicketsController@update');
Route::post('/ticket/{slug?}/delete','TicketsController@destroy');
Route::post('/comment', 'CommentsController@store');
Route::get('users/register', 'Auth\RegisterController@showRegistrationForm');
// Route::get('users/login', 'Auth\LoginController@showLoginForm');
Route::get('users/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('users/logout', 'Auth\LoginController@logout');
Route::post('users/register', 'Auth\RegisterController@register');
Route::get('/verify/token/{token}', 'Auth\VerificationController@verify')->name('auth.verify');  
Route::get('/verify/resend', 'Auth\VerificationController@resend')->name('auth.verify.resend');

// Route::get('users/profile', 'UserProfileController@index')->middleware('auth');

Route::group(array('middleware' => 'auth'), function () {

    Route::get('/', 'DashboardController@index');
    Route::get('/home', 'DashboardController@index');
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/leaderboard', 'LeaderboardController@index');
    Route::get('/challenges', 'ChallengesController@index');
    Route::get('/newsfeed', 'NewsFeedController@index');
    Route::get('users/profile', 'UserProfileController@index');
    Route::post('users/profile', 'UserProfileController@update');
});

Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'manager'), function () {
	Route::get('/', 'PagesController@home');
    Route::get('users', [ 'as' => 'admin.user.index', 'uses' => 'UsersController@index']);
    Route::get('users/{id?}/edit', 'UsersController@edit');
	Route::post('users/{id?}/edit','UsersController@update');
    Route::get('roles', 'RolesController@index');
    Route::get('roles/create', 'RolesController@create');
    Route::post('roles/create', 'RolesController@store');

    Route::get('posts', 'PostsController@index');
	Route::get('posts/create', 'PostsController@create');
	Route::post('posts/create', 'PostsController@store');
	Route::get('posts/{id?}/edit', 'PostsController@edit');
	Route::post('posts/{id?}/edit','PostsController@update');

	Route::get('categories', 'CategoriesController@index');
	Route::get('categories/create', 'CategoriesController@create');
	Route::post('categories/create', 'CategoriesController@store');
	Route::get('tokenmanager', 'ClientTokenManager@index');

});

Route::get('/blog', 'BlogController@index');
Route::get('/blog/{slug?}', 'BlogController@show');

Route::get('sendemail', function () {

    $data = array(
        'name' => "Sugbu Travel",
    );

    Mail::send('emails.welcome', $data, function ($message) {

        $message->from('sugbutravel@gmail.com', 'Sugbu Travel');

        $message->to('rtancio@icloud.com')->subject('Welcome to Sugbu Travel');

    });

    return "Your email has been sent successfully";

});
Auth::routes();
Route::group(array('middleware' => 'guest'), function () {
    Route::get('/', 'HomeController@index')->name('landingpage');
});

Route::get('user-location-detail',function(){
    $ip= \Request::ip();
    $location = \Location::get($ip);
    dd($location);
});