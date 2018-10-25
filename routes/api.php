<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/login','UserApiController@accessToken');
/* These are client credentials token routes */
Route::group(['middleware' =>'client'], function()
{
	Route::get('company/config', 'APIHealthQuestController@companyconfig');
	Route::get('/login', 'Auth\ApiLoginController@login');
	Route::post('/register', 'Auth\ApiRegisterController@register');
	Route::get('/password/email','Auth\ApiForgotPasswordController@sendresetpasswordemail');
	 // Route::post('/register', 'UserApiController@companyconfig');

	 // Route::get('/password_reset', 'UserApiController@companyconfig');
});

/* These are password token grant routes */
Route::group(['middleware' => ['web','auth:api']], function()
{
   // Route::post('/ticket','TicketApiController@store');
   // Route::get('/tickets','TicketApiController@index');
   // Route::get('/ticket/{slug?}','TicketApiController@show');
   // Route::put('/ticket/{slug?}','TicketApiController@update');
   // Route::delete('/ticket/{slug?}','TicketApiController@destroy');
   Route::get('logout', 'Auth\ApiLoginController@logout');
   Route::get('company/mainleaderboard','APIHealthQuestController@companyleaderboard');
   Route::get('company/challenge/leaderboard','APIHealthQuestController@getleaderboard');
   Route::get('company/challenges','APIHealthQuestController@companychallenges');
   Route::get('company/newsfeeds','APIHealthQuestController@companynewsfeeds');
   Route::get('company/events','APIHealthQuestController@companyevents');
   Route::get('company/challengestream','APIHealthQuestController@challengestream');
   Route::get('user/personaldashboard','APIUserController@personaldashboard');
   Route::put('user/profile','APIUserController@updateprofile');
   Route::put('user/profile/group','APIUserController@updategroupinfo');
   Route::put('user/profile/goal','APIUserController@updateuserdailygoal');
   Route::get('user/profile/goal','APIUserController@userdailygoal');
   Route::post('user/profile/avatar','APIUserController@updateavatar');
   Route::get('user/device/mymo','APIUserController@getmymodevice');
   Route::put('user/device/mymo','APIUserController@updatemymo');
   Route::put('user/activity/mymo','APIUserController@data_mymo');
   Route::put('user/activity/thirdparty','APIUserController@data_thirdparty');
   Route::get('user/healthychoices/dashboard','APIUserController@hcdashboard');
   Route::get('user/healthychoices/history','APIUserController@hchistory');
   Route::put('user/healthychoices/scanqr','APIUserController@scanQRcode');
   Route::get('user/rewards','APIUserController@userrewards');
   Route::put('user/rewards/acctnum','APIUserController@updaterewardsnum');
   Route::put('user/timezone','APIUserController@updatetz'); 
});



Route::post('LaravelApi/getToken','LaravelToken@index');
Route::post('LaravelApi','LaravelApi@index');

Route::group(['middleware' =>'client'], function()
{
//Route::post('LaravelApi','LaravelApi@index');
});



// Route::get('/companyconfig', function(Request $request) {
//     return response()->json([
// 	    'name' => 'Abigail',
// 	    'state' => 'CA'
// 	]);
// })->middleware('client');


 
// Route::middleware('auth:api')->get('/user', function (Request $request) {
 
//    return $request->user();
 
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


