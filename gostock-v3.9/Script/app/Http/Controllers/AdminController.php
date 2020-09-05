<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Categories;
use App\Models\UsersReported;
use App\Models\ImagesReported;
use App\Models\Images;
use App\Models\Stock;
use App\Models\CollectionsImages;
use App\Helper;
use App\Models\PaymentGateways;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Image;
use App\Models\Purchases;
use App\Models\Deposits;
use App\Models\Withdrawals;
use Mail;


class AdminController extends Controller {

	public function __construct(AdminSettings $settings) {
		$this->settings = $settings::first();
	}
	// START
	public function admin() {

		return view('admin.dashboard');

	}//<--- END METHOD

	// START
	public function categories() {

		$data      = Categories::orderBy('name')->get();

		return view('admin.categories')->withData($data);

	}//<--- END METHOD

	public function addCategories() {

		return view('admin.add-categories');

	}//<--- END METHOD

	public function storeCategories(Request $request) {

		$temp            = 'public/temp/'; // Temp
	  $path            = 'public/img-category/'; // Path General

		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$rules = array(
            'name'        => 'required',
	        'slug'        => 'required|ascii_only|unique:categories',
	        'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=457,min_height=359',
        );

		$this->validate($request, $rules);

		if( $request->hasFile('thumbnail') )	{

		$extension              = $request->file('thumbnail')->getClientOriginalExtension();
		$type_mime_shot   = $request->file('thumbnail')->getMimeType();
		$sizeFile                 = $request->file('thumbnail')->getSize();
		$thumbnail              = $request->slug.'-'.str_random(32).'.'.$extension;

		if ($request->file('thumbnail')->move($temp, $thumbnail)) {

			$image = Image::make($temp.$thumbnail);

			if ($image->width() == 457 && $image->height() == 359) {

					\File::copy($temp.$thumbnail, $path.$thumbnail);
					\File::delete($temp.$thumbnail);

			} else {
				$image->fit(457, 359)->save($temp.$thumbnail);

				\File::copy($temp.$thumbnail, $path.$thumbnail);
				\File::delete($temp.$thumbnail);
			}

			}// End File
		} // HasFile

		else {
			$thumbnail = '';
		}

		$sql              = New Categories();
		$sql->name        = trim($request->name);
		$sql->slug        = strtolower($request->slug);
		$sql->thumbnail = $thumbnail;
		$sql->mode        = $request->mode;
		$sql->save();

		\Session::flash('success_message', trans('admin.success_add_category'));

    	return redirect('panel/admin/categories');

	}//<--- END METHOD

	public function editCategories($id) {

		$categories = Categories::find( $id );

		return view('admin.edit-categories')->with('categories',$categories);

	}//<--- END METHOD

	public function updateCategories( Request $request ) {


		$categories  = Categories::find( $request->id );
		$temp            = 'public/temp/'; // Temp
	    $path            = 'public/img-category/'; // Path General

	    if( !isset($categories) ) {
			return redirect('panel/admin/categories');
		}

		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$rules = array(
            'name'        => 'required',
	        'slug'        => 'required|ascii_only|unique:categories,slug,'.$request->id,
	        'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=457,min_height=359',
	     );

		$this->validate($request, $rules);

		if( $request->hasFile('thumbnail') )	{

		$extension              = $request->file('thumbnail')->getClientOriginalExtension();
		$type_mime_shot   = $request->file('thumbnail')->getMimeType();
		$sizeFile                 = $request->file('thumbnail')->getSize();
		$thumbnail              = $request->slug.'-'.str_random(32).'.'.$extension;

		if( $request->file('thumbnail')->move($temp, $thumbnail) ) {

			$image = Image::make($temp.$thumbnail);

			if(  $image->width() == 457 && $image->height() == 359 ) {

					\File::copy($temp.$thumbnail, $path.$thumbnail);
					\File::delete($temp.$thumbnail);

			} else {
				$image->fit(457, 359)->save($temp.$thumbnail);

				\File::copy($temp.$thumbnail, $path.$thumbnail);
				\File::delete($temp.$thumbnail);
			}

			// Delete Old Image
			\File::delete($path.$categories->thumbnail);

			}// End File
		} // HasFile
		else {
			$thumbnail = $categories->image;
		}

		// UPDATE CATEGORY
		$categories->name        = $request->name;
		$categories->slug        = strtolower($request->slug);
		$categories->thumbnail  = $thumbnail;
		$categories->mode        = $request->mode;
		$categories->save();

		\Session::flash('success_message', trans('misc.success_update'));

    	return redirect('panel/admin/categories');

	}//<--- END METHOD

	public function deleteCategories($id){

		$categories        = Categories::find( $id );
		$thumbnail          = 'public/img-category/'.$categories->thumbnail; // Path General

		if( !isset($categories) || $categories->id == 1 ) {
			return redirect('panel/admin/categories');
		} else {

			$images_category   = Images::where('categories_id',$id)->get();

			// Delete Category
			$categories->delete();

			// Delete Thumbnail
			if ( \File::exists($thumbnail) ) {
				\File::delete($thumbnail);
			}//<--- IF FILE EXISTS

			//Update Categories Images
			if( isset( $images_category ) ) {
				foreach ($images_category as $key ) {
					$key->categories_id = 1;
					$key->save();
				}
			}

			return redirect('panel/admin/categories');
		}
	}//<--- END METHOD

	public function settings() {

		return view('admin.settings');

	}//<--- END METHOD

	public function saveSettings(Request $request) {

		Validator::extend('sell_option_validate', function($attribute, $value, $parameters) {
			// Count images for sale
			$imagesForSale = Images::where('item_for_sale', 'sale')->where('status', 'active')->count();

			if($value == 'off' && $imagesForSale > 0) {
				return false;
			}

			return true;

		});

		$messages = [
			'sell_option.sell_option_validate' => trans('misc.sell_option_validate')
		];

		$rules = array(
          'title'            => 'required',
	        'welcome_text' 	   => 'required',
	        'welcome_subtitle' => 'required',
	        'keywords'         => 'required',
	        'description'      => 'required',
	        'email_no_reply'   => 'required',
	        'email_admin'      => 'required',
					'link_terms'      => 'required|url',
					'link_privacy'      => 'required|url',
					'link_license'      => 'url',
					'sell_option' => 'sell_option_validate'
        );

		$this->validate($request, $rules, $messages);

		$sql                      = AdminSettings::first();
		$sql->title               = $request->title;
		$sql->welcome_text        = $request->welcome_text;
		$sql->welcome_subtitle    = $request->welcome_subtitle;
		$sql->keywords            = $request->keywords;
		$sql->description         = $request->description;
		$sql->email_no_reply      = $request->email_no_reply;
		$sql->email_admin         = $request->email_admin;
		$sql->link_terms         = $request->link_terms;
		$sql->link_privacy         = $request->link_privacy;
		$sql->link_license         = $request->link_license;
		$sql->captcha             = $request->captcha;
		$sql->registration_active = $request->registration_active;
		$sql->email_verification  = $request->email_verification;
		$sql->facebook_login  = $request->facebook_login;
		$sql->twitter_login = $request->twitter_login;
		$sql->google_ads_index    = $request->google_ads_index;
		$sql->sell_option    = $request->sell_option;
		$sql->who_can_sell   = $request->who_can_sell;
		$sql->who_can_upload   = $request->who_can_upload;
		$sql->free_photo_upload   = $request->free_photo_upload;
		$sql->show_counter       = $request->show_counter;
		$sql->show_categories_index       = $request->show_categories_index;
		$sql->show_images_index    = $request->show_images_index;
		$sql->show_watermark    = $request->show_watermark;
		$sql->lightbox         = $request->lightbox;
		$sql->save();

		if ($this->settings->who_can_upload == 'all' && $request->who_can_upload == 'admin') {
			User::where('role', '<>', 'admin')->update([
						'authorized_to_upload' => 'no'
					]);
		} elseif ($this->settings->who_can_upload == 'admin' && $request->who_can_upload == 'all') {
			User::where('role', '<>', 'admin')->update([
						'authorized_to_upload' => 'yes'
					]);
		}

		\Session::flash('success_message', trans('admin.success_update'));

    	return redirect('panel/admin/settings');

	}//<--- END METHOD

	public function settingsLimits() {

		return view('admin.limits');

	}//<--- END METHOD

	public function saveSettingsLimits(Request $request) {


		$sql                      = AdminSettings::first();
		$sql->result_request      = $request->result_request;
		$sql->limit_upload_user   = $request->limit_upload_user;
		$sql->daily_limit_downloads = $request->daily_limit_downloads;
		$sql->title_length        = $request->title_length;
		$sql->message_length      = $request->message_length;
		$sql->comment_length      = $request->comment_length;
		$sql->file_size_allowed   = $request->file_size_allowed;
		$sql->auto_approve_images = $request->auto_approve_images;
		$sql->downloads           = $request->downloads;
		$sql->tags_limit          = $request->tags_limit;
		$sql->description_length  = $request->description_length;
		$sql->min_width_height_image = $request->min_width_height_image;
		$sql->file_size_allowed_vector = $request->file_size_allowed_vector;

		$sql->save();

		\Session::flash('success_message', trans('admin.success_update'));

    	return redirect('panel/admin/settings/limits');

	}//<--- END METHOD

	public function members_reported() {

		$data = UsersReported::orderBy('id','DESC')->get();

		return view('admin.members_reported')->withData($data);

	}//<--- END METHOD

	public function delete_members_reported(Request $request) {

		$report = UsersReported::find($request->id);

		if( isset( $report ) ) {
			$report->delete();
		}

		return redirect('panel/admin/members-reported');

	}//<--- END METHOD

	public function images_reported() {

		$data = ImagesReported::orderBy('id','DESC')->get();

		//dd($data);

		return view('admin.images_reported')->withData($data);

	}//<--- END METHOD

	public function delete_images_reported(Request $request) {

		$report = ImagesReported::find($request->id);

		if( isset( $report ) ) {
			$report->delete();
		}

		return redirect('panel/admin/images-reported');

	}//<--- END METHOD

	public function images() {

		$query = request()->get('q');
		$sort = request()->get('sort');
		$pagination = 15;

		$data = Images::orderBy('id','desc')->paginate($pagination);

		// Search
		if( isset( $query ) ) {
		 	$data = Images::where('title', 'LIKE', '%'.$query.'%')
			->orWhere('tags', 'LIKE', '%'.$query.'%')
		 	->orderBy('id','desc')->paginate($pagination);
		 }

		// Sort
		if( isset( $sort ) && $sort == 'title' ) {
			$data = Images::orderBy('title','asc')->paginate($pagination);
		}

		if( isset( $sort ) && $sort == 'pending' ) {
			$data = Images::where('status','pending')->paginate($pagination);
		}

		if( isset( $sort ) && $sort == 'downloads' ) {
			$data = Images::join('downloads', 'images.id', '=', 'downloads.images_id')
					->groupBy('downloads.images_id')
					->orderBy( \DB::raw('COUNT(downloads.images_id)'), 'desc' )
					->select('images.*')
					->paginate( $pagination );
		}

		if( isset( $sort ) && $sort == 'likes' ) {
			$data = Images::join('likes', function($join){
				$join->on('likes.images_id', '=', 'images.id')->where('likes.status', '=', '1' );
			})
					->groupBy('likes.images_id')
					->orderBy( \DB::raw('COUNT(likes.images_id)'), 'desc' )
					->select('images.*')
					->paginate( $pagination );
		}

		return view('admin.images', ['data' => $data,'query' => $query, 'sort' => $sort ]);
	}//<--- End Method

	public function delete_image(Request $request) {

		//<<<<---------------------------------------------

		$image = Images::find($request->id);

		// Delete Notification
		$notifications = Notifications::where('destination',$request->id)
			->where('type', '2')
			->orWhere('destination',$request->id)
			->where('type', '3')
			->orWhere('destination',$request->id)
			->where('type', '6')
			->get();

		if (isset($notifications)) {
			foreach($notifications as $notification){
				$notification->delete();
			}
		}

		// Collections Images
	$collectionsImages = CollectionsImages::where('images_id', '=', $request->id)->get();
	 if( isset( $collectionsImages ) ){
			foreach($collectionsImages as $collectionsImage){
				$collectionsImage->delete();
			}
		}

		// Images Reported
		$imagesReporteds = ImagesReported::where('image_id', '=', $request->id)->get();
		 if( isset( $imagesReporteds ) ){
				foreach($imagesReporteds as $imagesReported){
					$imagesReported->delete();
				}
			}

		//<---- ALL RESOLUTIONS IMAGES
		$stocks = Stock::where('images_id', '=', $request->id)->get();

		foreach($stocks as $stock){

			// Delete Stock
			Storage::delete(config('path.uploads').$stock->type.'/'.$stock->name);

			// Delete Stock Vector
			Storage::delete(config('path.files').$stock->name);

			$stock->delete();

		}//<--- End foreach

		// Delete preview
		Storage::delete(config('path.preview').$image->preview);

		// Delete thumbnail
		Storage::delete(config('path.thumbnail').$image->thumbnail);

		$image->delete();

		return redirect('panel/admin/images');

	}//<--- End Method

	public function edit_image($id) {

		$data = Images::findOrFail($id);

		return view('admin.edit-image', ['data' => $data ]);

	}//<--- End Method

	public function update_image(Request $request) {

		$sql = Images::find($request->id);

		 $rules = array(
            'title'       => 'required|min:3|max:'.$this->settings->title_length,
            'description' => 'min:2|max:'.$this->settings->description_length.'',
	        'tags'        => 'required',

        );

		if( $request->featured == 'yes' && $sql->featured == 'no' ) {
			$featuredDate = \Carbon\Carbon::now();
		} elseif( $request->featured == 'yes' && $sql->featured == 'yes' ) {
			$featuredDate = $sql->featured_date;
		} else {
			$featuredDate = '';
		}

		$this->validate($request, $rules);

	    $sql->title         = $request->title;
		$sql->description   = $request->description;
		$sql->tags          = $request->tags;
		$sql->categories_id = $request->categories_id;
		$sql->status        = $request->status;
		$sql->featured      = $request->featured;
		$sql->featured_date = $featuredDate;


		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/images');
	}//<--- End Method

	public function profiles_social(){
		return view('admin.profiles-social');
	}//<--- End Method

	public function update_profiles_social(Request $request) {

		$sql = AdminSettings::find(1);

		$rules = array(
            'twitter'    => 'url',
            'facebook'   => 'url',
            'linkedin'   => 'url',
            'instagram'  => 'url',
						'youtube'  => 'url',
						'pinterest'  => 'url',
        );

		$this->validate($request, $rules);

	    $sql->twitter       = $request->twitter;
			$sql->facebook      = $request->facebook;
			$sql->linkedin      = $request->linkedin;
			$sql->instagram     = $request->instagram;
			$sql->youtube     = $request->youtube;
			$sql->pinterest     = $request->pinterest;

		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/profiles-social');
	}//<--- End Method

	public function google()
	{
		return view('admin.google');
	}//<--- END METHOD

	public function update_google(Request $request)
	{
		$sql = AdminSettings::first();

			$sql->google_adsense_index   = $request->google_adsense_index;
	    $sql->google_adsense   = $request->google_adsense;
		  $sql->google_analytics = $request->google_analytics;

		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/google');
	}//<--- End Method

	public function theme()
	{
		return view('admin.theme');

	}//<--- End method

	public function themeStore(Request $request)
	{
		$temp  = 'public/temp/'; // Temp
	  $path  = 'public/img/'; // Path
		$pathAvatar = config('path.avatar');
		$pathCover = config('path.cover');
		$pathCategory = 'public/img-category/'; // Path Category

		$rules = array(
          'logo'   => 'mimes:png',
					'favicon'   => 'mimes:png',
					'index_image_top'   => 'mimes:jpg,jpeg',
					'index_image_bottom'   => 'mimes:jpg,jpeg',
        );

		$this->validate($request, $rules);

		set_time_limit(0);
		ini_set('memory_limit', '512M');

		//========== LOGO
		if ($request->hasFile('logo'))	{

			$extension = $request->file('logo')->getClientOriginalExtension();
			$file      = 'logo-'.time().'.'.$extension;

		if ($request->file('logo')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->logo);
			}// End File

			$this->settings->logo = $file;
			$this->settings->save();
		} // HasFile

		//======== FAVICON
		if ($request->hasFile('favicon'))	{

		$extension  = $request->file('favicon')->getClientOriginalExtension();
		$file       = 'favicon-'.time().'.'.$extension;

		if ($request->file('favicon')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->favicon);
		}// End File

			$this->settings->favicon = $file;
			$this->settings->save();
		} // HasFile

		//======== index_image_top
		if ($request->hasFile('index_image_top'))	{

		$extension  = $request->file('index_image_top')->getClientOriginalExtension();
		$file       = 'header_index-'.time().'.'.$extension;

		if ($request->file('index_image_top')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->image_header);
			}// End File

			$this->settings->image_header = $file;
			$this->settings->save();
		} // HasFile

		//======== index_image_bottom
		if ($request->hasFile('index_image_bottom')) {

		$extension  = $request->file('index_image_bottom')->getClientOriginalExtension();
		$file       = 'cover-'.time().'.'.$extension;

		if ($request->file('index_image_bottom')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->image_bottom);
			}// End File

			$this->settings->image_bottom = $file;
			$this->settings->save();
		} // HasFile

		//======== Watermark
		if ($request->hasFile('watermark')) {

		$extension  = $request->file('watermark')->getClientOriginalExtension();
		$file       = 'watermark-'.time().'.'.$extension;

		if ($request->file('watermark')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->watermark);
			}// End File

			$this->settings->watermark = $file;
			$this->settings->save();
		} // HasFile

		//======== header_colors
		if ($request->hasFile('header_colors')) {

		$extension  = $request->file('header_colors')->getClientOriginalExtension();
		$file       = 'header_colors-'.time().'.'.$extension;

		if ($request->file('header_colors')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->header_colors);
			}// End File

			$this->settings->header_colors = $file;
			$this->settings->save();
		} // HasFile

		//======== header_cameras
		if ($request->hasFile('header_cameras')) {

		$extension  = $request->file('header_cameras')->getClientOriginalExtension();
		$file       = 'header_cameras-'.time().'.'.$extension;

		if ($request->file('header_cameras')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->header_cameras);
			}// End File

			$this->settings->header_cameras = $file;
			$this->settings->save();
		} // HasFile

		//======== avatar
		if ($request->hasFile('avatar')) {

			$extension  = $request->file('avatar')->getClientOriginalExtension();
			$file       = 'default-'.time().'.'.$extension;

		$imgAvatar  = Image::make($request->file('avatar'))->fit(180, 180, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		})->encode($extension);

		// Copy folder
		Storage::put($pathAvatar.$file, $imgAvatar, 'public');

		// Update Avatar all users
		User::where('avatar', $this->settings->avatar)->update([
					'avatar' => $file
				]);

		// Delete old Avatar
		Storage::delete(config('path.avatar').$this->settings->avatar);

			$this->settings->avatar = $file;
			$this->settings->save();
		} // HasFile

		//======== cover
		if ($request->hasFile('cover')) {

			$extension  = $request->file('cover')->getClientOriginalExtension();
			$file       = 'cover-'.time().'.'.$extension;

		// Copy folder
		$request->file('cover')->storePubliclyAs($pathCover, $file);

		// Update Avatar all users
		User::where('cover', $this->settings->cover)->update([
					'cover' => $file
				]);

		// Delete old Avatar
		Storage::delete(config('path.cover').$this->settings->cover);

			$this->settings->cover = $file;
			$this->settings->save();
		} // HasFile

		//======== img_category
		if ($request->hasFile('img_category')) {

		$extension  = $request->file('img_category')->getClientOriginalExtension();
		$file       = 'default-'.time().'.'.$extension;

		if ($request->file('img_category')->move($temp, $file)) {

			$image = Image::make($temp.$file);

			$image->fit(457, 359)->save($temp.$file);

			\File::copy($temp.$file, $pathCategory.$file);
			\File::delete($temp.$file);
			\File::delete($pathCategory.$this->settings->img_category);
			}// End File

			$this->settings->img_category = $file;
			$this->settings->save();
		} // HasFile

		//======== img_collection
		if ($request->hasFile('img_collection')) {

		$extension  = $request->file('img_collection')->getClientOriginalExtension();
		$file       = 'img-collection-'.time().'.'.$extension;

		if ($request->file('img_collection')->move($temp, $file)) {

			$image = Image::make($temp.$file);

			$image->fit(280, 160)->save($temp.$file);

			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			\File::delete($path.$this->settings->img_collection);
			}// End File

			$this->settings->img_collection = $file;
			$this->settings->save();
		} // HasFile

		//======= CLEAN CACHE
		\Artisan::call('cache:clear');

		return redirect('panel/admin/theme')
			 ->with('success_message', trans('misc.success_update'));

	}//<--- End method

	public function payments(){
		return view('admin.payments-settings');
	}//<--- End Method

	public function savePayments(Request $request) {

		$sql = AdminSettings::first();

		$rules = [
						'currency_code' => 'required|alpha',
						'currency_symbol' => 'required',
        ];

		$this->validate($request, $rules);

		$sql->currency_symbol  = $request->currency_symbol;
		$sql->currency_code    = strtoupper($request->currency_code);
		$sql->currency_position    = $request->currency_position;
		$sql->min_sale_amount   = $request->min_sale_amount;
		$sql->max_sale_amount   = $request->max_sale_amount;
		$sql->min_deposits_amount   = $request->min_deposits_amount;
		$sql->max_deposits_amount   = $request->max_deposits_amount;
		$sql->fee_commission        = $request->fee_commission;
		$sql->fee_commission_non_exclusive = $request->fee_commission_non_exclusive;
		$sql->amount_min_withdrawal    = $request->amount_min_withdrawal;
		$sql->decimal_format = $request->decimal_format;

		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/payments');
	}//<--- End Method

	public function purchases(){

		$data = Purchases::orderBy('id', 'desc')->paginate(30);

		return view('admin.purchases')->withData($data);
	}//<--- End Method

	public function deposits(){

		$data = Deposits::orderBy('id', 'desc')->paginate(30);

		return view('admin.deposits')->withData($data);
	}//<--- End Method

	public function withdrawals(){

		$data = Withdrawals::orderBy('id','DESC')->paginate(50);
		return view('admin.withdrawals', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function withdrawalsView($id){
		$data = Withdrawals::findOrFail($id);
		return view('admin.withdrawal-view', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function withdrawalsPaid(Request $request)
	{

		$data = Withdrawals::findOrFail($request->id);

		// Set Withdrawal as Paid
		$data->status    = 'paid';
		$data->date_paid = \Carbon\Carbon::now();
		$data->save();

		$user = $data->user();

		// Set Balance a zero
		$user->balance = 0;
		$user->save();

		//<------ Send Email to User ---------->>>
		$amount       = Helper::amountFormatDecimal($data->amount).' '.$this->settings->currency_code;
		$sender       = $this->settings->email_no_reply;
	  $titleSite    = $this->settings->title;
		$fullNameUser = $user->name ? $user->name : $user->username;
		$_emailUser   = $user->email;

		Mail::send('emails.withdrawal-processed', array(
					'amount'     => $amount,
					'fullname'   => $fullNameUser
		),
			function($message) use ($sender, $fullNameUser, $titleSite, $_emailUser)
				{
				    $message->from($sender, $titleSite)
									  ->to($_emailUser, $fullNameUser)
										->subject(trans('misc.withdrawal_processed').' - '.$titleSite);
				});
			//<------ Send Email to User ---------->>>

		return redirect('panel/admin/withdrawals');

	}//<--- End Method

	public function paymentsGateways($id) {

		$data = PaymentGateways::findOrFail($id);
		$name = ucfirst($data->name);

		return view('admin.'.str_slug($name).'-settings')->withData($data);
	}//<--- End Method

	public function savePaymentsGateways($id, Request $request) {

		$data = PaymentGateways::findOrFail($id);

		$input = $_POST;

		$this->validate($request, [
            'email'    => 'email',
        ]);

		$data->fill($input)->save();

		\Session::flash('success_message', trans('admin.success_update'));

    return back();
	}//<--- End Method

}
