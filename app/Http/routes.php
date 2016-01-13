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

## Halo 5
Route::controller('/h5/roster', 'Halo5\RosterController');
Route::get('/h5/profile/{gamertag}', 'Halo5\ProfileController@index');
Route::get('/h5/profile/check-for-update/{gamertag}', 'Halo5\ProfileController@checkForUpdate');

## Other
Route::controller('/comment', 'CommentController');
Route::controller('/calendar', 'CalendarController');
Route::controller('/admin', 'AdminController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');