<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
 |-----------------------------------
 | Index
 |--------- -------------------------
 */
Route::get('/', 'HomeController@index');
Route::get('home', function(){
	return redirect('/');
});

/*
 |-----------------------------------
 | Images Sections
 |--------- -------------------------
 */
Route::get('latest', 'HomeController@latest');
Route::get('featured', 'HomeController@featured');
Route::get('popular', 'HomeController@popular');
Route::get('most/commented', 'HomeController@commented');
Route::get('most/viewed', 'HomeController@viewed');
Route::get('most/downloads', 'HomeController@downloads');
Route::get('photos/premium', 'HomeController@premium');


/*
 |-----------------------------------
 | Authentication
 |--------- -------------------------
 */
Route::auth();

/*
 |-----------------------------------
 | Social Login
 |--------- -------------------------
 */
Route::group(['middleware' => 'guest'], function() {
	Route::get('oauth/{provider}', 'SocialAuthController@redirect')->where('provider', '(facebook|google|twitter)$');
	Route::get('oauth/{provider}/callback', 'SocialAuthController@callback')->where('provider', '(facebook|google|twitter)$');
});//<--- End Group guest

/*
 |
 |-----------------------------------
 | Default Sections
 |--------- -------------------------
 */

// Members
Route::get('members', function(){

	$data = App\Models\Query::users();

	return view('default.members')->with($data);
});

 // Categories
Route::get('categories', function(){

	$data = App\Models\Categories::where('mode','on')->orderBy('name')->get();

	return view('default.categories')->withData($data);
});

//<---- Categories List
Route::get('category/{slug}','HomeController@category');

// Tags
 Route::get('tags', function(){

	$data = App\Models\Images::select(DB::raw('GROUP_CONCAT(tags SEPARATOR ",") as tags'))->where('status','active')->get();

	return view('default.tags')->withData($data);
});

Route::get('tags/{tags}','HomeController@tags' );

// Collections
Route::get('collections','HomeController@collections' );

// Collections Detail
Route::get('{user}/collection/{id}/{slug?}','UserController@collectionDetail' );

// Cameras
Route::get('cameras/{cameras}','HomeController@cameras' );

// Colors
Route::get('colors/{colors}','HomeController@colors' );

// Search
Route::get('search', 'HomeController@getSearch');

// Photo Details
Route::get('photo/{id}/{slug?}','ImagesController@show');

// Logout
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('contact','HomeController@contact');
Route::post('contact','HomeController@contactStore');

/*
 |
 |-----------------------------------
 | Verify Account
 |--------- -------------------------
 */
Route::get('verify/account/{confirmation_code}', 'HomeController@getVerifyAccount')->where('confirmation_code','[A-Za-z0-9]+');

/*
 |
 |------------------------
 | Pages Static Custom
 |--------- --------------
 */
 Route::get('page/{page}','PagesController@show')->where('page','[^/]*' );

 /*
 |
 |----------------------------
 | Sitemaps
 |--------- ------------------
 */
Route::get('sitemaps.xml', function() {
	return response()->view('default.sitemaps')->header('Content-Type', 'application/xml');
});

/*
 |
 |-----------------------------------
 | Ajax Request
 |--------- -------------------------
 */
Route::post('ajax/like', 'AjaxController@like');
Route::post('ajax/follow', 'AjaxController@follow');
Route::get('ajax/notifications', 'AjaxController@notifications');
Route::get('ajax/users', 'AjaxController@users');
Route::get('ajax/search', 'AjaxController@search');
Route::get('ajax/latest', 'AjaxController@latest');
Route::get('ajax/featured', 'AjaxController@featured');
Route::get('ajax/popular', 'AjaxController@popular');
Route::get('ajax/commented', 'AjaxController@commented');
Route::get('ajax/viewed', 'AjaxController@viewed');
Route::get('ajax/downloads', 'AjaxController@downloads');
Route::get('ajax/category', 'AjaxController@category');
Route::get('ajax/tags', 'AjaxController@tags');
Route::get('ajax/cameras', 'AjaxController@camera');
Route::get('ajax/colors', 'AjaxController@colors');
Route::get('ajax/user/images', 'AjaxController@userImages');
Route::get('ajax/comments', 'AjaxController@comments');
Route::get('ajax/premium', 'AjaxController@premium');


/*
 |
 |-----------------------------------
 | User Views LOGGED
 |--------- -------------------------
 */
Route::group(['middleware' => 'auth'], function() {


	//<---- Upload
	 Route::get('upload', function(){

	 if( Auth::user()->authorized_to_upload == 'yes' ) {
	 	return view('images.upload');
	 } else {
	 	return redirect('/');
	 }

	});


	// Edit Photo
	Route::get('edit/photo/{id}','ImagesController@edit');
	Route::post('update/photo','ImagesController@update');

	// Delete Photo 6
	Route::post('delete/photo/{id}','ImagesController@destroy');

	// Account Settings
	Route::get('account','UserController@account');
	Route::post('account','UserController@update_account');

	// Password
	Route::get('account/password','UserController@password');
	Route::post('account/password','UserController@update_password');

	// Delete Account
	Route::get('account/delete','UserController@delete');
	Route::post('account/delete','UserController@delete_account');

	// Upload Avatar
	Route::post('upload/avatar','UserController@upload_avatar');

	// Upload Cover
	Route::post('upload/cover','UserController@upload_cover');

	// Likes
	Route::get('likes','UserController@userLikes');

	// Feed
	Route::get('feed','UserController@followingFeed');

	// Photos Pending
	Route::get('photos/pending','UserController@photosPending');

	// Notifications
	Route::get('notifications','UserController@notifications');
	Route::get('notifications/delete','UserController@notificationsDelete');

	Route::post('upload','ImagesController@create');

	// Report Photo
	Route::post('report/photo','ImagesController@report');

	// Report User
	Route::post('report/user','UserController@report');

	// Collections
	Route::post('collection/store','CollectionController@store');

	// Collection Edit
	Route::post('collection/edit','CollectionController@edit');

	// Collectin Delete
	Route::get('collection/delete/{id}','CollectionController@destroy');

	// Add Image to Collection
	Route::get('collection/{id}/i/{image}', 'CollectionController@addImageCollection')->where(array( 'id' => '[0-9]+','image' => '[0-9]+'));

	// Comments
	Route::post('comment/store','CommentsController@store');

	// Comments Delete
	Route::post('comment/delete','CommentsController@destroy');

	// Comment Like
	Route::post('comment/like','CommentsController@like');

	//======= DASHBOARD ================//
	// Dashboard
	Route::get('user/dashboard','DashboardController@dashboard');

	// Photos
	Route::get('user/dashboard/photos','DashboardController@photos');

	// Sales
	Route::get('user/dashboard/sales','DashboardController@sales');

	// Purchases
	Route::get('user/dashboard/purchases','DashboardController@purchases');

	// Deposits
	Route::get('user/dashboard/deposits','DashboardController@deposits');

	// Add Funds
	Route::get('user/dashboard/add/funds','DashboardController@addFunds');
	Route::post('user/dashboard/add/funds','AddFundsController@send');

	// Withdrawals
	Route::get('user/dashboard/withdrawals','DashboardController@showWithdrawal');

	// Request withdrawal
	Route::post('request/withdrawal','DashboardController@withdrawal');

	Route::get('user/dashboard/withdrawals/configure','DashboardController@withdrawalsConfigureView');

	Route::post('user/withdrawals/configure/{type}','DashboardController@withdrawalConfigure');

	Route::post('delete/withdrawal/{id}','DashboardController@withdrawalDelete');

	// Purchase Photo
	Route::post('purchase/stock/{token_id}','ImagesController@purchase');

});//<------ End User Views LOGGED

// See all Comments Likes
	Route::post('comments/likes','CommentsController@getLikes');

/*
 |
 |-----------------------------------
 | User Views
 |--------- -------------------------
 */

//<----------- USERS VIEWS ---------->>>

// Downloads Images
Route::group(['middleware' => 'downloads'], function() {
	Route::post('download/stock/{token_id}','ImagesController@download');
});


/*
 |
 |-----------------------------------
 | Profile User
 |-----------------------------------
 */

Route::get('{slug}', 'UserController@profile')->where('slug','[A-Za-z0-9\_-]+');
Route::get('{slug}/followers', 'UserController@followers')->where('slug','[A-Za-z0-9\_-]+');
Route::get('{slug}/following', 'UserController@following')->where('slug','[A-Za-z0-9\_-]+');
Route::get('{slug}/collections', 'UserController@collections')->where('slug','[A-Za-z0-9\_-]+');

/*
 |
 |-----------------------------------
 | Admin Panel
 |--------- -------------------------
 */
Route::group(['middleware' => 'role'], function() {


	// Upgrades
	Route::get('update/{version}','UpgradeController@update');

	// Dashboard
	Route::get('panel/admin','AdminController@admin');

	// Categories
	Route::get('panel/admin/categories','AdminController@categories');
	Route::get('panel/admin/categories/add','AdminController@addCategories');
	Route::post('panel/admin/categories/add','AdminController@storeCategories');
	Route::get('panel/admin/categories/edit/{id}','AdminController@editCategories')->where(array( 'id' => '[0-9]+'));
	Route::post('panel/admin/categories/update','AdminController@updateCategories');
	Route::get('panel/admin/categories/delete/{id}','AdminController@deleteCategories')->where(array( 'id' => '[0-9]+'));

	// Settings
	Route::get('panel/admin/settings','AdminController@settings');
	Route::post('panel/admin/settings','AdminController@saveSettings');

	// Images
	Route::get('panel/admin/images','AdminController@images');
	Route::post('panel/admin/images/delete','AdminController@delete_image');

	Route::get('panel/admin/images/{id}','AdminController@edit_image');
	Route::post('panel/admin/images/update','AdminController@update_image');

	// Limits
	Route::get('panel/admin/settings/limits','AdminController@settingsLimits');
	Route::post('panel/admin/settings/limits','AdminController@saveSettingsLimits');

	// Members
	Route::resource('panel/admin/members', 'AdminUserController',
		['names' => [
		    'edit'    => 'user.edit',
		    'destroy' => 'user.destroy'
		 ]]
	);

	// Members Reported
	Route::get('panel/admin/members-reported','AdminController@members_reported');
	Route::post('panel/admin/members-reported','AdminController@delete_members_reported');

	// Images Reported
	Route::get('panel/admin/images-reported','AdminController@images_reported');
	Route::post('panel/admin/images-reported','AdminController@delete_images_reported');

	// Pages
	Route::resource('panel/admin/pages', 'PagesController',
		['names' => [
		    'edit'    => 'pages.edit',
		    'destroy' => 'pages.destroy'
		 ]]
	);

	// Profiles Social
	Route::get('panel/admin/profiles-social','AdminController@profiles_social');
	Route::post('panel/admin/profiles-social','AdminController@update_profiles_social');

	// Google
	Route::get('panel/admin/google','AdminController@google');
	Route::post('panel/admin/google','AdminController@update_google');

	//***** Languages
	Route::get('panel/admin/languages','LangController@index');

	// ADD NEW
	Route::get('panel/admin/languages/create','LangController@create');

	// ADD NEW POST
	Route::post('panel/admin/languages/create','LangController@store');

	// EDIT LANG
	Route::get('panel/admin/languages/edit/{id}','LangController@edit')->where( array( 'id' => '[0-9]+'));

	// EDIT LANG POST
	Route::post('panel/admin/languages/edit/{id}', 'LangController@update')->where(array( 'id' => '[0-9]+'));

	// DELETE LANG
	Route::resource('panel/admin/languages', 'LangController',
		['names' => [
		    'destroy' => 'languages.destroy'
		 ]]
	);

	// BULK UPLOAD
	Route::get('panel/admin/bulk-upload','bulkUploadController@bulkUpload');
	Route::post('panel/admin/bulk-upload','bulkUploadController@bulkUploadStore');

	// THEME
	Route::get('panel/admin/theme','AdminController@theme');
	Route::post('panel/admin/theme','AdminController@themeStore');

	// Payments
	Route::get('panel/admin/payments','AdminController@payments');
	Route::post('panel/admin/payments','AdminController@savePayments');

	Route::get('panel/admin/payments/{id}','AdminController@paymentsGateways');
	Route::post('panel/admin/payments/{id}','AdminController@savePaymentsGateways');

	// Purchases
	Route::get('panel/admin/purchases','AdminController@purchases');

	// Deposits
	Route::get('panel/admin/deposits','AdminController@deposits');

	//Withdrawals
	Route::get('panel/admin/withdrawals','AdminController@withdrawals');
	Route::get('panel/admin/withdrawal/{id}','AdminController@withdrawalsView');
	Route::post('panel/admin/withdrawals/paid/{id}','AdminController@withdrawalsPaid');


});//<--- End Group Role


Route::get('lang/{id}', function($id){

	$lang = App\Models\Languages::where('abbreviation', $id)->firstOrFail();

	Session::put('locale', $lang->abbreviation);

   return back();

})->where(array( 'id' => '[a-z]+'));

// PayPal IPN
Route::post('paypal/ipn','PayPalController@paypalIpn');

/*
 |
 |------------------------
 | v3.2
 |--------- --------------
 */

Route::get('install/{addon}','InstallController@install');

// Payments Gateways
Route::get('payment/paypal', 'PayPalController@show')->name('paypal');

Route::get('payment/stripe', 'StripeController@show')->name('stripe');
Route::post('payment/stripe/charge', 'StripeController@charge');

Route::get('user/dashboard/downloads','DashboardController@downloads')->middleware('auth');
Route::get('files/preview/{size}/{path}', 'ImagesController@image')->where('path', '.*');
Route::get('assets/preview/{path}.{ext}', 'ImagesController@preview');