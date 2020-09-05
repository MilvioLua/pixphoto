<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Collections;
use App\Models\Purchases;

class UpgradeController extends Controller {

	public function __construct( AdminSettings $settings, Images $images, Collections $collections, User $user, Categories $categories) {
	 $this->user         = $user::first();
	 $this->settings     = $settings::first();
	 $this->images       = $images::first();
	 $this->collections  = $collections::first();
	 $this->categories   = $categories::first();
 }

 /**
	* Move a file
	*
	*/
 private static function moveFile($file, $newFile, $copy)
 {
	 if (File::exists($file) && $copy == false) {
			 File::move($file, $newFile);
	 } else if(File::exists($newFile) && isset($copy)) {
			 File::copy($newFile, $file);
	 }
 }

 /**
	* Copy a directory
	*
	*/
 private static function moveDirectory($directory, $destination, $copy)
 {
	 if(File::isDirectory($directory) && $copy == false) {
			 File::moveDirectory($directory, $destination);
	 } else if(File::isDirectory($destination) && isset($copy)) {
			 File::copyDirectory($destination, $directory);
	 }
 }

	public function update($version) {

		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.trans('admin.upgrade_done').' <a style="text-decoration: none; color: #F50;" href="'.url('/').'">'.trans('error.go_home').'</a></h2>';
		//<<---- Version 1.3 ----->>
		if( $version == '1.3' ) {

			if( isset($this->settings->google_adsense_index) ) {
				return redirect('/');
			} else {

				Schema::table('admin_settings', function($table){
					$table->text('google_adsense_index')->after('min_width_height_image');
				 });

				return $upgradeDone;
			}
		}//<<---- Version 1.3 ----->>

		//<<---- Version 1.6 ----->>
		if( $version == '1.6' ) {

			// Create Table languages
				if (!Schema::hasTable('languages')) {
					Schema::create('languages', function($table)
							 {
									 $table->increments('id');
									 $table->string('name', 100);
									 $table->string('abbreviation', 32);
							 });

				 if( Schema::hasTable('languages') ) {
					 DB::table('languages')->insert(
							 array('name' => 'English', 'abbreviation' => 'en')
					 );
				 }
			 }// <<--- End Create Table languages

			 // Add Instagram
			 if( !Schema::hasColumn('users', 'instagram') ) {
				 Schema::table('users', function($table){
 					$table->string('instagram', 200)->after('authorized_to_upload');
 				 });
			 }// <<--- End Add Instagram

			 // Add Link to Pages Terms and Privacy
			 if( !Schema::hasColumn('admin_settings', 'link_terms', 'link_privacy' ) ) {
				 Schema::table('admin_settings', function($table){
 					$table->string('link_terms', 200)->after('google_adsense_index');
					$table->string('link_privacy', 200)->after('google_adsense_index');
 				 });
			 }// <<--- End Add Link to Pages Terms and Privacy


					return $upgradeDone;

		}//<<---- Version 1.6 ----->>

		//<<---- Version 2.0 ----->>
		if( $version == '2.0' ) {

			// Add Fields in Users Table
			if( !Schema::hasColumn('users', 'funds', 'balance', 'payment_gateway', 'bank') ) {
				Schema::table('users', function($table){
					$table->unsignedInteger('funds');
					$table->decimal('balance', 10, 2);
					$table->string('payment_gateway', 50);
					$table->text('bank');
				});
			}// <<-- Add Fields in Users Table

			// Add Fields in Images Table
			if( !Schema::hasColumn('images', 'price', 'item_for_sale', 'funds') ) {
				Schema::table('images', function($table){
					$table->unsignedInteger('price');
					$table->enum('item_for_sale', ['free', 'sale'])->default('free');
				});
			}// <<--- End Add Fields in Images Table

			 // Add Fields in AdminSettings
			 if( ! Schema::hasColumn('admin_settings',
			 		'paypal_sandbox',
					'paypal_account',
					'fee_commission',
					'stripe_secret_key',
					'stripe_public_key',
					'max_deposits_amount',
					'min_deposits_amount',
					'min_sale_amount',
					'max_sale_amount',
					'amount_min_withdrawal',
					'enable_paypal',
					'enable_stripe',
					'currency_position',
					'currency_symbol',
					'currency_code',
					'handling_fee'

					) ) {

				 Schema::table('admin_settings', function($table){
 					$table->enum('paypal_sandbox', ['true', 'false'])->default('true');
					$table->string('paypal_account', 200);
					$table->unsignedInteger('fee_commission');

					$table->string('stripe_secret_key', 200);
					$table->string('stripe_public_key', 200);

					$table->unsignedInteger('max_deposits_amount');
					$table->unsignedInteger('min_deposits_amount');
					$table->unsignedInteger('min_sale_amount');
					$table->unsignedInteger('max_sale_amount');
					$table->unsignedInteger('amount_min_withdrawal');

					$table->enum('enable_paypal', ['0', '1'])->default('0');
					$table->enum('enable_stripe', ['0', '1'])->default('0');

					$table->enum('currency_position', ['left', 'right'])->default('left');
					$table->string('currency_symbol', 200);
					$table->string('currency_code', 200);
					$table->unsignedInteger('handling_fee');

 				 });
			 }// <<--- End Add Fields in AdminSettings

			 // Create table Deposits
			 if( ! Schema::hasTable('deposits')) {

					 Schema::create('deposits', function ($table) {

					 $table->engine = 'InnoDB';
					 $table->increments('id');
					 $table->unsignedInteger('user_id');
					 $table->string('txn_id', 200);
					 $table->unsignedInteger('amount');
					 $table->string('payment_gateway', 100);
					 $table->timestamp('date');
			 });

		 }// <<< --- Create table Deposits

		 // Create table Purchases
		 if( ! Schema::hasTable('purchases')) {

				 Schema::create('purchases', function ($table) {

				 $table->engine = 'InnoDB';
				 $table->increments('id');
				 $table->unsignedInteger('images_id');
				 $table->unsignedInteger('user_id');
				 $table->unsignedInteger('price');
				 $table->timestamp('date');
				 $table->enum('approved', ['0', '1'])->default('1');
				 $table->decimal('earning_net_seller', 10, 2);
				 $table->decimal('earning_net_admin', 10, 2);
		 });

	 }// <<< --- Create table Purchases

	 // Create table Purchases
	 if( ! Schema::hasTable('withdrawals')) {

			 Schema::create('withdrawals', function ($table) {

			 $table->engine = 'InnoDB';
			 $table->increments('id');
			 $table->unsignedInteger('user_id');
			 $table->enum('status', ['pending', 'paid'])->default('pending');
			 $table->string('amount', 50);
			 $table->timestamp('date');
			 $table->string('gateway', 100);
			 $table->text('account');
			 $table->timestamp('date_paid')->default('0000-00-00 00:00:00');
	 });

 }// <<< --- Create table Purchases

 return $upgradeDone;

}//<<---- Version 2.0 ----->>

//<<---- Version 2.3 ----->>
if( $version == '2.3' ) {

	// AdminSettings
	if( ! Schema::hasColumn('admin_settings',
		 'sell_option',
		 'ip'
		 ) ) {

			 Schema::table('admin_settings', function($table){
				$table->enum('sell_option', ['on', 'off'])->default('on');
				});
		 } // Schema hasColumn AdminSettings

		 // User
	 	if( ! Schema::hasColumn('users','ip') ) {

	 			 Schema::table('users', function($table) {
					 $table->string('ip', 30);
	 				});
	 		 } // Schema hasColumn User
	return $upgradeDone;

}//<<---- Version 2.3 ----->>

//<------------------------ Version 2.5
if( $version == '2.5' ) {

	// Create table payment_gateways
if( ! Schema::hasTable('payment_gateways') ) {

		 Schema::create('payment_gateways', function ($table) {

			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('name', 50);
			$table->string('type');
			$table->enum('enabled', ['1', '0'])->default('1');
			$table->enum('sandbox', ['true', 'false'])->default('true');
			$table->decimal('fee', 3, 1);
			$table->decimal('fee_cents', 2, 2);
			$table->string('email', 80);
			$table->string('token', 200);
			$table->string('key', 255);
			$table->string('key_secret', 255);
			$table->text('bank_info');
		});

		\DB::table('payment_gateways')->insert([
			[
				'name' => 'PayPal',
				'type' => 'normal',
				'enabled' => $this->settings->enable_paypal,
				'fee' => 5.4,
				'fee_cents' => 0.30,
				'email' => $this->settings->paypal_account,
				'key' => '',
				'key_secret' => '',
				'bank_info' => '',
				'token' => '02bGGfD9bHevK3eJN06CdDvFSTXsTrTG44yGdAONeN1R37jqnLY1PuNF0mJRoFnsEygyf28yePSCA1eR0alQk4BX89kGG9Rlha2D2KX55TpDFNR5o774OshrkHSZLOFo2fAhHzcWKnwsYDFKgwuaRg',
		],
		[
			'name' => 'Stripe',
			'type' => 'card',
			'enabled' => $this->settings->enable_stripe,
			'fee' => 2.9,
			'fee_cents' => 0.30,
			'email' => '',
			'key' => $this->settings->stripe_public_key,
			'key_secret' => $this->settings->stripe_secret_key,
			'bank_info' => '',
			'token' => 'asfQSGRvYzS1P0X745krAAyHeU7ZbTpHbYKnxI2abQsBUi48EpeAu5lFAU2iBmsUWO5tpgAn9zzussI4Cce5ZcANIAmfBz0bNR9g3UfR4cserhkJwZwPsETiXiZuCixXVDHhCItuXTPXXSA6KITEoT',
	]
		]);

	}// End create table payment_gateways

	return $upgradeDone;
}//<---------------------- Version 2.5

//<------------------------ Version 2.7
if( $version == '2.7' ) {

	// Insert on AdminSettings
	if( ! Schema::hasColumn('admin_settings', 'show_images_index', 'file_size_allowed_vector', '') ) {
			Schema::table('admin_settings', function($table) {
			$table->enum('show_images_index', ['latest', 'featured', 'both'])->default('latest');
			$table->enum('show_watermark', ['1', '0'])->default('1');
			$table->unsignedInteger('file_size_allowed_vector')->default(1024);
		});
	}

	// Insert on Images
	if( ! Schema::hasColumn('images', 'vector') ) {
			Schema::table('images', function($table) {
			$table->string('vector', 3);
		});
	}

	if (!file_exists('public/uploads/files')) {
		mkdir('public/uploads/files', 0777, true);
	}

	return $upgradeDone;

}//<---------------------- Version 2.7

//<------------------------ Version 3.2
if( $version == '3.2' ) {

	// Insert on Images
	if (! Schema::hasColumn('purchases', 'type')) {
			Schema::table('purchases', function($table) {
				$table->string('type', 25);
		});

		if (Schema::hasColumn('purchases', 'type')) {

			foreach (Purchases::all() as $key) {
				Purchases::whereId($key->id)->update(['date' => $key->date, 'type' => 'large']);
			}

		}
	}

	return $upgradeDone;

}//<---------------------- Version 3.2

		//<------------------------ Version 3.3
		if( $version == '3.3' ) {

			// Add Link to License
			if (! Schema::hasColumn('admin_settings', 'link_license', 'decimal_format', 'version') ) {
				Schema::table('admin_settings', function($table){
				 $table->string('link_license', 200);
				 $table->enum('decimal_format', ['comma', 'dot'])->default('dot');
				 $table->string('version', 5);
				});

				if (Schema::hasColumn('admin_settings', 'version')) {
					AdminSettings::whereId(1)->update([
								'version' => '3.3'
							]);
				}

			}// <<--- End Add Link to License

			// Insert on Purchases
			if (! Schema::hasColumn('purchases', 'license', 'purchase_code', 'order_id')) {
					Schema::table('purchases', function($table) {
						$table->string('license', 25);
							$table->string('order_id', 25);
							$table->string('purchase_code', 40);
				});

				if (Schema::hasColumn('purchases', 'license', 'purchase_code', 'order_id')) {

					foreach (Purchases::all() as $key) {
						Purchases::whereId($key->id)->update([
									'date' => $key->date,
									'license' => 'regular',
									'purchase_code' => implode( '-', str_split( substr( strtolower( md5( time() . mt_rand( 1000, 9999 ) ) ), 0, 27 ), 5 ) ),
									'order_id' => substr(strtolower( md5( microtime() . mt_rand( 1000, 9999 ) ) ), 0, 15 ),
								]);
					}
				}
			}// Insert on Purchases

			return $upgradeDone;
		}//<---------------------- Version 3.3

		//<<---- Version 3.4 ----->>
		if($version == '3.4') {

			if ($this->settings->version == '3.4') {
				return redirect('/');
			}

			if ($this->settings->version != '3.3' || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 3.3</h2>";
			}

			file_put_contents(
					'.env',
					"\nFILESYSTEM_DRIVER=default\n\nWAS_ACCESS_KEY_ID=\nWAS_SECRET_ACCESS_KEY=\nWAS_DEFAULT_REGION=\nWAS_BUCKET=\n\nDOS_ACCESS_KEY_ID=\nDOS_SECRET_ACCESS_KEY=\nDOS_DEFAULT_REGION=\nDOS_BUCKET=\n",
					FILE_APPEND
			);

			/*for ($i=1; $i <= 39; ++$i) {
				// code...
				echo '$this->moveFile($file'.$i.', $path'.$i.', $copy);<br>';
			}
			exit;*/

				//============ Starting moving files...
	 			$path           = "v$version/";
	 			$pathAdmin      = "v$version/admin/";
	 			$copy           = false;

	 			//============== Files ================//
	 			$file1 = $path.'Helper.php';
				$file2 = $path.'path.php';
				$file3 = $path.'filesystems.php';
				$file4 = $path.'ImagesController.php';
				$file5 = $path.'UserController.php';
				$file6 = $path.'AdminController.php';
				$file7 = $path.'AdminUserController.php';
				$file8 = $path.'HomeController.php';
				$file9 = $path.'AjaxController.php';
				$file10 = $path.'CommentsController.php';
				$file11 = $path.'userTraits.php';
				$file12 = $path.'functions.js';
				$file13 = $pathAdmin.'dashboard.blade.php';
				$file14 = $pathAdmin.'purchases.blade.php';
				$file15 = $pathAdmin.'images.blade.php';
				$file16 = $pathAdmin.'edit-image.blade.php';
				$file17 = $pathAdmin.'members.blade.php';
				$file18 = $pathAdmin.'layout.blade.php';
				$file19 = $pathAdmin.'edit-member.blade.php';
				$file20 = $path.'dashboard.blade.php';
				$file21 = $path.'layout.blade.php';
				$file22 = $path.'photos.blade.php';
				$file23 = $path.'purchases.blade.php';
				$file24 = $path.'sales.blade.php';
				$file25 = $path.'cameras.blade.php';
				$file26 = $path.'category.blade.php';
				$file27 = $path.'colors.blade.php';
				$file28 = $path.'search.blade.php';
				$file29 = $path.'tags-show.blade.php';

				$file30 = $path.'edit.blade.php';
				$file31 = $path.'show.blade.php';
				$file32 = $path.'upload.blade.php';

				$file33 = $path.'collections.blade.php';
				$file34 = $path.'comments.blade.php';
				$file35 = $path.'images.blade.php';
				$file36 = $path.'navbar.blade.php';
				$file37 = $path.'users.blade.php';

				$file38 = $path.'explore.blade.php';
				$file39 = $path.'profile.blade.php';

				$file40 = $path.'smartphoto.min.css';
				$file41 = $path.'smartphoto.min.js';

				//============== Paths ================//
	 			$path1 = app_path('Helper.php');
				$path2 = config_path('path.php');
	 			$path3 = config_path('filesystems.php');

				$path4 = app_path('Http/Controllers/ImagesController.php');
				$path5 = app_path('Http/Controllers/UserController.php');
				$path6 = app_path('Http/Controllers/AdminController.php');
				$path7 = app_path('Http/Controllers/AdminUserController.php');
				$path8 = app_path('Http/Controllers/HomeController.php');
				$path9 = app_path('Http/Controllers/AjaxController.php');
				$path10 = app_path('Http/Controllers/CommentsController.php');
				$path11 = app_path('Http/Controllers/Traits/userTraits.php');
				$path12 = public_path('js/functions.js');

				$path13 = resource_path('views/admin/dashboard.blade.php');
				$path14 = resource_path('views/admin/purchases.blade.php');
				$path15 = resource_path('views/admin/images.blade.php');
				$path16 = resource_path('views/admin/edit-image.blade.php');
				$path17 = resource_path('views/admin/members.blade.php');
				$path18 = resource_path('views/admin/layout.blade.php');
				$path19 = resource_path('views/admin/edit-member.blade.php');

				$path20 = resource_path('views/dashboard/dashboard.blade.php');
				$path21 = resource_path('views/dashboard/layout.blade.php');
				$path22 = resource_path('views/dashboard/photos.blade.php');
				$path23 = resource_path('views/dashboard/purchases.blade.php');
				$path24 = resource_path('views/dashboard/sales.blade.php');

				$path25 = resource_path('views/default/cameras.blade.php');
				$path26 = resource_path('views/default/category.blade.php');
				$path27 = resource_path('views/default/colors.blade.php');
				$path28 = resource_path('views/default/search.blade.php');
				$path29 = resource_path('views/default/tags-show.blade.php');

				$path30 = resource_path('views/images/edit.blade.php');
				$path31 = resource_path('views/images/show.blade.php');
				$path32 = resource_path('views/images/upload.blade.php');

				$path33 = resource_path('views/includes/collections.blade.php');
				$path34 = resource_path('views/includes/comments.blade.php');
				$path35 = resource_path('views/includes/images.blade.php');
				$path36 = resource_path('views/includes/navbar.blade.php');
				$path37 = resource_path('views/includes/users.blade.php');

				$path38 = resource_path('views/index/explore.blade.php');
				$path39 = resource_path('views/users/profile.blade.php');

				$path40 = public_path('css/smartphoto.min.css');
				$path41 = public_path('js/smartphoto.min.js');

	 			//============== Moving Files ================//
				$this->moveFile($file1, $path1, $copy);
				$this->moveFile($file2, $path2, $copy);
				$this->moveFile($file3, $path3, $copy);
				$this->moveFile($file4, $path4, $copy);
				$this->moveFile($file5, $path5, $copy);
				$this->moveFile($file6, $path6, $copy);
				$this->moveFile($file7, $path7, $copy);
				$this->moveFile($file8, $path8, $copy);
				$this->moveFile($file9, $path9, $copy);
				$this->moveFile($file10, $path10, $copy);
				$this->moveFile($file11, $path11, $copy);
				$this->moveFile($file12, $path12, $copy);
				$this->moveFile($file13, $path13, $copy);
				$this->moveFile($file14, $path14, $copy);
				$this->moveFile($file15, $path15, $copy);
				$this->moveFile($file16, $path16, $copy);
				$this->moveFile($file17, $path17, $copy);
				$this->moveFile($file18, $path18, $copy);
				$this->moveFile($file19, $path19, $copy);
				$this->moveFile($file20, $path20, $copy);
				$this->moveFile($file21, $path21, $copy);
				$this->moveFile($file22, $path22, $copy);
				$this->moveFile($file23, $path23, $copy);
				$this->moveFile($file24, $path24, $copy);
				$this->moveFile($file25, $path25, $copy);
				$this->moveFile($file26, $path26, $copy);
				$this->moveFile($file27, $path27, $copy);
				$this->moveFile($file28, $path28, $copy);
				$this->moveFile($file29, $path29, $copy);
				$this->moveFile($file30, $path30, $copy);
				$this->moveFile($file31, $path31, $copy);
				$this->moveFile($file32, $path32, $copy);
				$this->moveFile($file33, $path33, $copy);
				$this->moveFile($file34, $path34, $copy);
				$this->moveFile($file35, $path35, $copy);
				$this->moveFile($file36, $path36, $copy);
				$this->moveFile($file37, $path37, $copy);
				$this->moveFile($file38, $path38, $copy);
				$this->moveFile($file39, $path39, $copy);
				$this->moveFile($file40, $path40, $copy);
				$this->moveFile($file41, $path41, $copy);

	 			//============ End Moving Files ===============//

				// Delete folder
				if ($copy == false) {
				 File::deleteDirectory("v$version");
			 }

				 // Update Version
 				$this->settings->whereId(1)->update([
 							'version' => $version
 						]);

			 return $upgradeDone;
		}//<<---- Version 3.4 ----->>

		//<<---- Version 3.5 ----->>
		if($version == '3.5') {

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != '3.4' || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 3.4</h2>";
			}

			/*for ($i=1; $i <= 39; ++$i) {
				echo '$this->moveFile($file'.$i.', $path'.$i.', $copy);<br>';
			}
			exit;*/

			// Add title_length
			if (! Schema::hasColumn('admin_settings', 'title_length') ) {
				Schema::table('admin_settings', function($table){
				 $table->unsignedInteger('title_length');
				});

				if (Schema::hasColumn('admin_settings', 'title_length')) {
					AdminSettings::whereId(1)->update([
								'title_length' => 50
							]);
				}
			}// <<--- End Add title_length

			DB::table('reserved')->insert([
				['name' => 'core'],
				['name' => 'update']
			]
		);

		$replace = "Route::get('/logout', 'Auth\LoginController@logout');\nRoute::get('contact','HomeController@contact');\nRoute::post('contact','HomeController@contactStore');";

	 $fileConfig = 'routes/web.php';
	 file_put_contents(
				 $fileConfig,
				 str_replace("Route::get('/logout', 'Auth\LoginController@logout');", $replace,
				 file_get_contents($fileConfig)
			 ));


				//============ Starting moving files...
	 			$path           = "v$version/";
	 			$pathAdmin      = "v$version/admin/";
	 			$copy           = false;

	 			//============== Files ================//
	 			$file1 = $path.'Helper.php';
				$file2 = $path.'AdminController.php';
				$file3 = $path.'ImagesController.php';
				$file4 = $path.'Upload.php';
				$file5 = $pathAdmin.'layout.blade.php';
				$file6 = $pathAdmin.'limits.blade.php';
				$file7 = $path.'dropzone.min.css';
				$file8 = $path.'dropzone.min.js';
				$file9 = $path.'HomeController.php';
				$file10 = $path.'StripeController.php';
				$file11 = $path.'contact.blade.php';
				$file12 = $path.'contact-email.blade.php';
				$file13 = $path.'add-funds.blade.php';
				$file14 = $path.'footer.blade.php';

				//============== Paths ================//
	 			$path1 = app_path('Helper.php');
				$path2 = app_path('Http/Controllers/AdminController.php');
				$path3 = app_path('Http/Controllers/ImagesController.php');
				$path4 = app_path('Http/Controllers/Traits/Upload.php');
				$path5 = resource_path('views/admin/layout.blade.php');
				$path6 = resource_path('views/admin/limits.blade.php');
				$path7 = public_path('js/dropzone.min.css');
				$path8 = public_path('js/dropzone.min.js');
				$path9 = app_path('Http/Controllers/HomeController.php');
				$path10 = app_path('Http/Controllers/StripeController.php');
				$path11 = resource_path('views/default/contact.blade.php');
				$path12 = resource_path('views/emails/contact-email.blade.php');
				$path13 = resource_path('views/dashboard/add-funds.blade.php');
				$path14 = resource_path('views/includes/footer.blade.php');

	 			//============== Moving Files ================//
				$this->moveFile($file1, $path1, $copy);
				$this->moveFile($file2, $path2, $copy);
				$this->moveFile($file3, $path3, $copy);
				$this->moveFile($file4, $path4, $copy);
				$this->moveFile($file5, $path5, $copy);
				$this->moveFile($file6, $path6, $copy);
				$this->moveFile($file7, $path7, $copy);
				$this->moveFile($file8, $path8, $copy);
				$this->moveFile($file9, $path9, $copy);
				$this->moveFile($file10, $path10, $copy);
				$this->moveFile($file11, $path11, $copy);
				$this->moveFile($file12, $path12, $copy);
				$this->moveFile($file13, $path13, $copy);
				$this->moveFile($file14, $path14, $copy);


	 			//============ End Moving Files ===============//

				// Delete folder
				if ($copy == false) {
				 File::deleteDirectory("v$version");
			 }

				 // Update Version
 				$this->settings->whereId(1)->update([
 							'version' => $version
 						]);

			 return $upgradeDone;
		}
		//<<---- Version 3.4 ----->>

 }// <<--- method update

}
