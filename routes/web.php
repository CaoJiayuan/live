<?php

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('upload/img', 'InfoController@uploadImg')->middleware('throttle:10,1', 'cors');

Route::get('/api/chat/{roomId}/users', 'ChatController@users');
Route::get('/t', function (\App\Repository\UserRepository $repository) {
  return $repository->reword(1, 10, \App\User::find(10774));
});
Route::group(['middleware' => ['auth']], function () {
  Route::get('/', 'HomeController@home');
  Route::get('/admin', 'HomeController@admin');
  Route::get('/download', 'HomeController@download');

  Route::group(['prefix' => 'user'], function () {
    Route::get('/', 'UserController@getUser');
    Route::get('{userId}/robots', 'UserController@getRobots');
    Route::post('/', 'UserController@editUser');
    Route::post('/{roomId}/sign', 'UserController@sign');
    Route::post('/{roomId}/reword', 'UserController@reword');
    Route::post('/{roomId}/gift', 'UserController@gift');
    Route::post('/password', 'UserController@editPassword');
  });
  Route::group(['prefix' => 'room'], function () {
    Route::get('{roomId}/votes', 'VoteController@getVotes');
    Route::get('{roomId}/users', 'RoomController@users');
    Route::get('{roomId}/children', 'RoomController@children');
    Route::get('{roomId}/notice', 'RoomController@notice');
    Route::get('{roomId}/backgrounds', 'RoomController@backgrounds');
    Route::get('{roomId}/info', 'RoomController@getInfo');
    Route::get('{roomId}/banners', 'RoomController@getBanners');
    Route::get('{roomId}/services', 'RoomController@getServices');
    Route::get('{roomId}/maskings', 'RoomController@getMaskings');
    Route::get('{roomId}/popup', 'RoomController@popup');
    Route::get('{roomId}/schedules', 'RoomController@schedules');
    Route::get('{roomId}/goods', 'RoomController@goods');
    Route::get('{roomId}/gifts', 'RoomController@gifts');
    Route::get('{roomId}/credits', 'RoomController@creditRules');
    Route::post('vote', 'VoteController@postVote');
  });

  Route::group(['prefix' => 'chat'], function () {
    Route::get('{topic}/histories', 'ChatController@histories');
    Route::post('{roomId}/leave', 'ChatController@leave');
  });

  Route::group(['prefix' => 'order'], function () {
    Route::post('{id}/order', 'OrderController@order');
  });

});

Route::post('/join', 'RoomController@join');
Route::post('/leave', 'RoomController@leave');