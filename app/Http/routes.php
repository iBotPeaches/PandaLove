<?php

Route::get('/profile/check-for-update/{gamertag}', 'ProfileController@checkForUpdate');
Route::get('/profile/manual-update/{seo}', 'ProfileController@manualUpdate');
Route::get('/profile/{gamertag}/{characterId?}', 'ProfileController@index');

Route::controller('/games', 'GameController');
Route::controller('/admin', 'AdminController');
Route::controller('/roster', 'RosterController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');