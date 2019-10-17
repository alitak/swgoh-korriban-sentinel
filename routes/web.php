<?php

Route::resource('/', 'SentinelController');

// @todo move to command
Route::get('/syncUnits', 'SyncController@units');

Route::get('/t', 'TestController@index');
