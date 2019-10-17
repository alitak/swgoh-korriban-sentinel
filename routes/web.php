<?php

Route::resource('/', 'SentinelController');

// @todo move to command
Route::get('/syncCharacters', 'SyncController@characters');

Route::get('/t', 'TestController@index');
