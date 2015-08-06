<?php
Route::controller('api/v1', 'ApiV1Controller');

Route::get('/profile/check-for-update/{gamertag}', 'ProfileController@checkForUpdate');
Route::get('/profile/manual-update/{seo}', 'ProfileController@manualUpdate');
Route::get('/profile/{gamertag}/{characterId?}', 'ProfileController@index');

Route::controller('/calendar', 'CalendarController');
Route::controller('/games', 'GameController');
Route::controller('/admin', 'AdminController');
Route::controller('/roster', 'RosterController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');
