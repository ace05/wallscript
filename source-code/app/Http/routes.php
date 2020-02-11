<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => ['dConfig']], function () {
	Route::get('/image/{aspect}/{size}/{filename}', 'ImageController@getImage');

	//Admin Routes
	Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']), function(){
	    Route::get('/', ['as' => 'dashboard', 'uses' => 'AdminHomeController@getHome']);

	    //Settings
	    Route::get('/settings/{slug}', ['as' => 'adminSettings', 'uses' => 'AdminSettingsController@getSettings']);
	    Route::post('/settings/{slug}', ['uses' => 'AdminSettingsController@updateSettings']);
	    //Users
	    Route::get('/users', ['as' => 'getUsersList', 'uses' => 'AdminUsersController@getUsersList']);
	    Route::get('/users/status/{type}/{id}', ['as' => 'updateUserStatus', 'uses' => 'AdminUsersController@updateUserStatus']);
	    //Posts
	    Route::get('/posts', ['as' => 'getPostList', 'uses' => 'AdminPostController@getPostList']);
	    Route::get('/posts/delete/{id}', ['as' => 'deleteAdminPost', 'uses' => 'AdminPostController@deletePost']);
	   	//Updates

	   	Route::get('/updates', ['as' => 'getUpdatesList', 'uses' => 'AdminUpdatesController@getUpdatesList']);
	   	Route::get('/updates/delete/{id}/{post}/{type}', ['as' => 'deleteAdminUpdate', 'uses' => 'AdminUpdatesController@deleteAdminUpdate']);
	   	
	   	//Comments
	   	Route::get('/comments', ['as' => 'getCommentsList', 'uses' => 'AdminCommentController@getCommentList']);
	   	Route::get('/comment/delete/{id}/{postId}/{updateId}', ['as' => 'deleteAdminComment', 'uses' => 'AdminCommentController@deleteAdminComment']);
	   	
	});


	Route::group(['middleware' => ['guest']], function () {
	    Route::get('/login',['as' => 'login', 'uses' => 'AuthController@getLogin']);
	    Route::post('/login',['uses' => 'AuthController@postLogin']);
	    Route::get('/register',['as' => 'register', 'uses' => 'AuthController@getRegister']);
	    Route::get('/register/{provider}',['as' => 'socialRegister', 'uses' => 'AuthController@socialRegistration']);
	    Route::get('/process/{provider}',['as' => 'socialRegisterProcess', 'uses' => 'AuthController@processSocialRegistration']);
	    Route::post('/register',['uses' => 'AuthController@postRegister']);
	    Route::get('/activate/{activationCode}',['as' => 'activation', 'uses' => 'AuthController@activateAccount']);
	    Route::controllers([
		   'password' => 'PasswordController',
		]);
	});
	Route::group(['middleware' => ['auth']], function () {
	    Route::get('/home',['as' => 'userHome', 'uses' => 'HomeController@getUserHome']);
	    Route::get('/logout',['as' => 'logout', 'uses' => 'AuthController@getLogout']);
	    Route::get('/{username}', ['as' => 'userProfile', 'uses' => 'UserController@getProfile']);
	    Route::get('/{username}/friends', ['as' => 'userFriendsList', 'uses' => 'UserController@getUserFriendsList']);
	    Route::get('/{username}/photos/uploaded', ['as' => 'userPhotos', 'uses' => 'UserController@getUserUploadedPhotos']);

	    Route::post('/updatePost', ['as' => 'updatePost', 'uses' => 'UpdateController@postUpdate']);
	    Route::post('/addComment', ['as' => 'addComment', 'uses' => 'UpdateController@addComment']);
	    Route::get('/getAllcomments/{postId}', ['as' => 'getAllcomments', 'uses' => 'UpdateController@getAllcomments']);
	    Route::get('/addLike/{postId}/{updateId}/{type}', ['as' => 'addLike', 'uses' => 'UpdateController@addLike']);
	    Route::get('/removeLike/{id}/{postId}/{updateId}', ['as' => 'removeLike', 'uses' => 'UpdateController@removeLike']);
	    Route::get('/getAllLikes/{postId}', ['as' => 'getAllLikes', 'uses' => 'UpdateController@getAllLikes']);
	    Route::get('/deletePost/{updateId}/{postId}/{type}', ['as' => 'deletePost', 'uses' => 'UpdateController@deletePost']);
	    Route::get('/deleteComment/{commentId}/{updateId}/{postId}/{type}', ['as' => 'deleteComment', 'uses' => 'UpdateController@deleteComment']);
	    Route::get('/shareUpdate/{postId}', ['as' => 'shareUpdate', 'uses' => 'UpdateController@shareUpdate']);
	    Route::get('/notifications/count', ['as' => 'getNotificationCount', 'uses' => 'UpdateController@getNotificationCount']);
	    Route::get('/notifications/list', ['as' => 'getUserNotificationList', 'uses' => 'UpdateController@getUserNotificationList']);
	    Route::get('/friendRequest/list', ['as' => 'getFriendRequestList', 'uses' => 'UpdateController@getFriendRequestList']);	  
	   	Route::get('/update/view/{id}', ['as' => 'viewUpdate', 'uses' => 'UpdateController@viewUpdate']);
	   	Route::get('/notifications/all', ['as' => 'notifications', 'uses' => 'UserController@viewAllNotifications']);
	   	Route::get('/friendRequests/all', ['as' => 'allFriendRequests', 'uses' => 'UserController@viewAllFriendRequests']);
	   	Route::get('/add/friend/{id}', ['as' => 'addFriend', 'uses' => 'UserController@addFriend']);
	   	Route::get('/cancel/request/{id}', ['as' => 'cancelRequest', 'uses' => 'UserController@cancelRequest']);
	   	Route::get('/friendRequest/confirm/{id}', ['as' => 'confirmRequest', 'uses' => 'UserController@confirmFriend']);
	   	Route::get('/unfriend/request/{id}', ['as' => 'unFriend', 'uses' => 'UserController@unFriend']);

	   	//Profile
	   	Route::post('/coverPhoto/upload', ['as' => 'coverPhotoUpload', 'uses' => 'UserController@uploadCoverPhoto']);
	   	Route::post('/coverPhoto/update', ['as' => 'updateCoverPhoto', 'uses' => 'UserController@updateCoverPhoto']);
	   	Route::post('/profile/upload', ['as' => 'profilePhotoUpload', 'uses' => 'UserController@uploadProfilePhoto']);
	   	Route::get('/profile/settings', ['as' => 'settings', 'uses' => 'UserController@getProfileSettings']);
	   	Route::post('/profile/update', ['as' => 'profileUpdate', 'uses' => 'UserController@updateProfileSettings']);
	   	Route::post('/profile/changePassword', ['as' => 'changePassword', 'uses' => 'UserController@changePassword']);
	   	Route::get('/friends/autocomplete', ['as' => 'searchFriends', 'uses' => 'UserController@searchFriends']);
	   	Route::post('/friends/tags', ['as' => 'friendTags', 'uses' => 'UserController@getTaggedFriends']);

	   	//Messages
	   	Route::get('/message/conversation/{username?}', ['as' => 'conversation', 'uses' => 'MessageController@index']);
	   	Route::post('/message/send', ['as' => 'sendMessage', 'uses' => 'MessageController@sendMessage']);
	   	Route::get('/messages/list', ['as' => 'getConversationList', 'uses' => 'MessageController@getConversationList']);
	   	Route::get('/messages/markread/{conversationId}', ['as' => 'markRead', 'uses' => 'MessageController@markAsRead']);

	   	//Search 
	   	Route::get('/people/search', ['as' => 'searchPeople', 'uses' => 'SearchController@getSearchResults']);
	});

	Route::group(['middleware' => ['guest']], function () {
		Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
	});
});


