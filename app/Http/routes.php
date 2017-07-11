<?php

//# Overwatch
Route::controller('/overwatch/api/v1', 'Overwatch\ApiV1Controller');
Route::controller('/overwatch/roster', 'Overwatch\RosterController');
Route::controller('/overwatch/stats', 'Overwatch\StatsController');
Route::get('/overwatch/profile/check-for-update/{gamertag}/{console}', 'Overwatch\ProfileController@checkForUpdate');
Route::get('/overwatch/profile/manual-update/{gamertag}/{console}', 'Overwatch\ProfileController@manualUpdate');
Route::get('/overwatch/profile/{gamertag}/{console}/', 'Overwatch\ProfileController@index');

//# Destiny
Route::controller('/destiny/api/v1', 'Destiny\ApiV1Controller');
Route::controller('/destiny/roster', 'Destiny\RosterController');
Route::controller('/destiny/games', 'Destiny\GameController');
Route::get('/destiny/platform-switch/{gamertag}', 'Destiny\ProfileController@platformSwitch');
Route::get('/destiny/profile/check-for-update/{console}/{gamertag}', 'Destiny\ProfileController@checkForUpdate');
Route::get('/destiny/profile/manual-update/{console}/{seo}', 'Destiny\ProfileController@manualUpdate');
Route::get('/destiny/profile/{console}/{gamertag}/{characterId?}', 'Destiny\ProfileController@index');

//# Xbox
Route::controller('/xbox/api/v1', 'Xbox\ApiV1Controller');

//# Halo 5
Route::controller('/h5/api/v1', 'Halo5\ApiV1Controller');
Route::controller('/h5/api/panda', 'Halo5\LeafApiController');
Route::controller('/h5/roster', 'Halo5\RosterController');
Route::controller('/h5/games', 'Halo5\GameController');
Route::controller('/h5/stats', 'Halo5\StatsController');
Route::get('/h5/profile/get-recent-games/{gamertag}/{page}', 'Halo5\ProfileController@getRecentGames');
Route::get('/h5/profile/{gamertag}', 'Halo5\ProfileController@index');
Route::get('/h5/profile/manual-update/{seo}', 'Halo5\ProfileController@manualUpdate');
Route::get('/h5/profile/check-for-update/{gamertag}', 'Halo5\ProfileController@checkForUpdate');

//# Admin
Route::controller('/backstage/destiny', 'Backstage\DestinyController');
Route::controller('/backstage/halo5', 'Backstage\Halo5Controller');
Route::controller('/backstage', 'Backstage\IndexController');

//# Other
Route::controller('/account', 'AccountController');
Route::controller('/comment', 'CommentController');
Route::controller('/calendar', 'CalendarController');
Route::controller('/usercp', 'UserCpController');
Route::controller('/auth', 'AuthController');
Route::controller('/', 'HomeController');
