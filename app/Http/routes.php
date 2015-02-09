<?php

Route::get('/profile/check-for-update/{gamertag}', 'ProfileController@checkForUpdate');
Route::get('/profile/{gamertag}', 'ProfileController@index');
Route::controller('/admin', 'AdminController');
Route::controller('/roster', 'RosterController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');