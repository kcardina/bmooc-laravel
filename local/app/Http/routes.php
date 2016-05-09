<?php

use App\Http\Controllers\BmoocController;

Route::get('/', 'BmoocController@index');
Route::get('/start', 'BmoocController@index');

Route::get('topic/{left}/{answer?}', ['uses'=>'BmoocController@showTopic']);
Route::post('topic/new', 'BmoocController@newTopic');
Route::post('instruction/new', 'BmoocController@newInstruction');

Route::get('search/{author?}/{tag?}/{keyword?}', 'BmoocController@searchDiscussions');
Route::post('comment', 'BmoocController@commentDiscussion');

Route::get('artefact/{id}', 'BmoocController@getImage');
Route::get('artefact/{id}/thumbnail', 'BmoocController@getImageThumbnail');
Route::get('artefact/{id}/original', 'BmoocController@getImageOriginal');

Route::get('json/instruction/{thread}', 'BmoocJsonController@instruction');
Route::get('json/topic/{id}/answers', 'BmoocJsonController@answers');
Route::get('json/topic/{id}/answers/search/{author?}/{tag?}/{keyword?}', 'BmoocJsonController@answers');
Route::get('json/topic/{id}', 'BmoocJsonController@discussion');

//Route::get('login/{provider?}', ['uses'=>'Auth\AuthController@login', 'as' => 'login']);
//Route::get('logout', 'Auth\AuthController@getLogout');

// Authentication and registration
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('auth/error', function ()    {
    return view('errors/login');
});
Route::post('auth/login', 'Auth\AuthController@postLogin');
//Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/logout', 'BmoocController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('login/{provider?}', ['uses'=>'Auth\AuthController@login', 'as' => 'login']);
Route::get('logout', 'Auth\AuthController@getLogout');

Route::post('feedback', 'BmoocController@feedback');

Route::get('admin', function(){
    return Redirect::to('admin/data/basic');
});
Route::get('admin/data', function(){
    return Redirect::to('admin/data/basic');
});
Route::get('admin/data/basic', 'AdminController@basic');
Route::get('admin/data/progress', 'AdminController@progress');
Route::get('admin/data/tree', 'AdminController@tree');
Route::get('admin/data/topics', 'AdminController@topics');
Route::get('admin/actions', function(){
    return Redirect::to('admin/actions/thumbnails');
});
Route::get('admin/actions/thumbnails', 'AdminController@getThumbnails');
Route::post('admin/actions/thumbnails', 'AdminController@postThumbnails');
Route::get('admin/actions/tags', 'AdminController@getTags');
Route::post('admin/actions/tags', 'AdminController@postTags');
