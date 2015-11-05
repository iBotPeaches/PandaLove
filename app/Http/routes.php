<?php
Route::controller('/destiny/api/v1', 'Destiny\ApiV1Controller');
Route::controller('/xbox/api/v1', 'Xbox\ApiV1Controller');

Route::get('/profile/check-for-update/{gamertag}', 'ProfileController@checkForUpdate');
Route::get('/profile/manual-update/{seo}', 'ProfileController@manualUpdate');
Route::get('/profile/{gamertag}/{characterId?}', 'ProfileController@index');

Route::controller('/calendar', 'CalendarController');
Route::controller('/games', 'GameController');
Route::controller('/admin', 'AdminController');
Route::controller('/destiny/roster', 'Destiny\RosterController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');