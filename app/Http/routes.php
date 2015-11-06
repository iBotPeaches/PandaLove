<?php

## Destiny
Route::controller('/destiny/api/v1', 'Destiny\ApiV1Controller');
Route::controller('/destiny/roster', 'Destiny\RosterController');
Route::controller('/destiny/games', 'Destiny\GameController');
Route::get('/destiny/profile/check-for-update/{gamertag}', 'Destiny\ProfileController@checkForUpdate');
Route::get('/destiny/profile/manual-update/{seo}', 'Destiny\ProfileController@manualUpdate');
Route::get('/destiny/profile/{gamertag}/{characterId?}', 'Destiny\ProfileController@index');

## Xbox
Route::controller('/xbox/api/v1', 'Xbox\ApiV1Controller');

## Other
Route::controller('/comment', 'CommentController');
Route::controller('/calendar', 'CalendarController');
Route::controller('/admin', 'AdminController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');