<?php

use App\Http\Controllers\BmoocController;

Route::get('/', 'BmoocController@index');
Route::get('/start', 'BmoocController@index');
Route::get('help', 'BmoocController@help');

Route::get('topic/{left}/{answer?}', ['uses'=>'BmoocController@showTopic']);
Route::post('topic/new', 'BmoocController@newTopic');
Route::post('instruction/new', 'BmoocController@newInstruction');

Route::get('discussion/{links}/{rechts}/{pre?}', ['uses'=>'BmoocController@showDiscussion']);
Route::get('discussion/{encodedlink?}', array('as'=>'discussionEncoded', 'uses'=>'BmoocController@showDiscussionEncoded'));
//Route::get('search/{tag?}', 'BmoocController@searchDiscussionsByTag');
//Route::get('search/author/{author?}', 'BmoocController@searchDiscussionsByAuthor');
//Route::get('tags/{start}', 'TagsController@searchTags');
Route::get('search/{author?}/{tag?}/{keyword?}', 'BmoocController@searchDiscussions');
Route::post('comment', 'BmoocController@commentDiscussion');

Route::get('json/instruction/{thread}', 'BmoocJsonController@instruction');
Route::get('json/topic/{id}/answers', 'BmoocJsonController@answers');
Route::get('json/topic/{id}', 'BmoocJsonController@discussion');

Route::get('login/{provider?}', ['uses'=>'Auth\AuthController@login', 'as' => 'login']);
Route::get('logout', 'Auth\AuthController@getLogout');
Route::get('datavis', 'BmoocController@datavis');
