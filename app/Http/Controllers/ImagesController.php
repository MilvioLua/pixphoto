<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Images;
use App\Models\Followers;
use App\Models\Like;
use App\Models\ImagesReported;
use App\Models\Stock;
use App\Models\AdminSettings;
use App\Models\Downloads;
use App\Models\Notifications;
use App\Models\Visits;
use App\Models\Collections;
use App\Models\CollectionsImages;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Image;
use App\Models\Purchases;
use Illuminate\Support\Facades\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureFactory;

class ImagesController extends Controller {

	use Traits\Upload;

	 public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

	 protected function validatorUpdate(array $data)
	 {
    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$sizeAllowed = $this->settings->file_size_allowed * 1024;

		$dimensions = explode('x',$this->settings->min_width_height_image);

		if ($this->settings->currency_position == 'right') {
			$currencyPosition =  2;
		} else {
			$currencyPosition =  null;
		}

		$messages = array (
		'photo.required' => trans('misc.please_select_image'),
    "photo.max"   => trans('misc.max_size').' '.Helper::formatBytes( $sizeAllowed, 1 ),
		"price.required_if" => trans('misc.price_required'),
		'price.min' => trans('misc.price_minimum_sale'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
		'price.max' => trans('misc.price_maximum_sale'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),

	);

		// Create Rules
		return Validator::make($data, [
					'title'       => 'required|min:3|max:'.$this->settings->title_length.'',
						'description' => 'min:2|max:'.$this->settings->description_length.'',
					'tags'        => 'required',
					'price' => 'required_if:item_for_sale,==,sale|integer|min:'.$this->settings->min_sale_amount.'|max:'.$this->settings->max_sale_amount.''
				], $messages);

    }

	 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
	 public function index()
	 {
	 	$data = Images::all();

		return view('admin.images')->withData($data);
	 }

	/**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function show($id, $slug = null)
	{
		$response = Images::findOrFail($id);

		if (Auth::check() && $response->user_id != Auth::user()->id && $response->status == 'pending' && Auth::user()->role != 'admin') {
			abort(404);
		} else if (Auth::guest() && $response->status == 'pending') {
			abort(404);
		}

		$uri = $this->request->path();

		if (str_slug( $response->title) == '') {

				$slugUrl  = '';
			} else {
				$slugUrl  = '/'.str_slug( $response->title );
			}

			$url_image = 'photo/'.$response->id.$slugUrl;

			//<<<-- * Redirect the user real page * -->>>
			$uriImage     =  $this->request->path();
			$uriCanonical = $url_image;

			if ($uriImage != $uriCanonical) {
				return redirect($uriCanonical);
			}

			//<--------- * Visits * ---------->
			$user_IP = request()->ip();
			$date = time();

			if (Auth::check()) {
				// SELECT IF YOU REGISTERED AND VISITED THE PUBLICATION
				$visitCheckUser = $response->visits()->where('user_id',Auth::user()->id)->first();

				if (! $visitCheckUser && Auth::user()->id != $response->user()->id) {
					$visit = new Visits;
					$visit->images_id = $response->id;
					$visit->user_id  = Auth::user()->id;
					$visit->ip       = $user_IP;
					$visit->save();
				}

			} else {

				// IF YOU SELECT "UNREGISTERED" ALREADY VISITED THE PUBLICATION
				$visitCheckGuest = $response->visits()->where('user_id',0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if ($visitCheckGuest)	{
				  $dateGuest = strtotime($visitCheckGuest->date) + (7200); // 2 Hours

			}

				if (empty( $visitCheckGuest->ip)) {
				   	$visit = new Visits;
					$visit->images_id = $response->id;
					$visit->user_id  = 0;
					$visit->ip       = $user_IP;
					$visit->save();
			   } else if($dateGuest < $date) {
			   		$visit = new Visits;
					$visit->images_id = $response->id;
					$visit->user_id  = 0;
					$visit->ip       = $user_IP;
					$visit->save();
			   }

			}//<--------- * Visits * ---------->

			if (Auth::check()) {

				// FOLLOW ACTIVE
			 	$followActive = Followers::where( 'follower', Auth::user()->id )
			 	->where('following',$response->user()->id)
				->where('status', '1')
			 	->first();

	       if ($followActive) {
	       	  $textFollow   = trans('users.following');
						$icoFollow    = '-ok';
						$activeFollow = 'btnFollowActive';
	       } else {
	       		$textFollow   = trans('users.follow');
						$icoFollow    = '-plus';
						$activeFollow = '';
	       }

				   // LIKE ACTIVE
				   $likeActive = Like::where( 'user_id', Auth::user()->id )
				   ->where('images_id',$response->id)
				   ->where('status','1')
				   ->first();

			       if ($likeActive) {
			       	  $textLike   = trans('misc.unlike');
							  $icoLike    = 'fa fa-heart';
							  $statusLike = 'active';
			       } else {
			       		$textLike   = trans('misc.like');
							  $icoLike    = 'fa fa-heart-o';
								$statusLike = '';
			       }

				   // ADD TO COLLECTION
				   $collections = Collections::where('user_id',Auth::user()->id)->orderBy('id','asc')->get();

			 }//<<<<---- *** END AUTH ***

				// All Images resolutions
				$stockImages = $response->stock;

				$resolution = explode('x', Helper::resolutionPreview($stockImages[1]->resolution));
				$previewWidth = $resolution[0];
				$previewHeight = $resolution[1];

				// Similar Photos
				$arrayTags  = explode(",",$response->tags);
				$countTags = count( $arrayTags );

				$images = Images::where('categories_id',$response->categories_id)
				->whereStatus('active')
				->where(function($query) use ($arrayTags, $countTags) {
					for ($k = 0; $k < $countTags; ++$k) {
						 $query->orWhere('tags', 'LIKE', '%'.$arrayTags[$k].'%');
					}
				})
				->where('id', '<>',$response->id)
				->orderByRaw('RAND()')
				->take(10)
				->get();

				// Comments
				$comments_sql = $response->comments()->where('status','1')->orderBy('date', 'desc')->paginate(10);


    	return view('images.show')->with([
				 'response' => $response,
				 'textFollow' => $textFollow ?? null,
				 'icoFollow' => $icoFollow ?? null,
				 'activeFollow' => $activeFollow ?? null,
				 'textLike'   => $textLike ?? null,
				 'icoLike' => $icoLike ?? null,
				 'statusLike' => $statusLike ?? null,
				 'collections' => $collections ?? null,
				 'stockImages' => $stockImages,
				 'previewWidth' => $previewWidth,
				 'previewHeight' => $previewHeight,
				 'images' => $images,
				 'comments_sql' => $comments_sql
			]
			);

	}//<--- End Method

	/**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function edit($id)
	{
		$data = Images::findOrFail($id);

		if( $data->user_id != Auth::user()->id ) {
			abort('404');
		}

    	return view('images.edit')->withData($data);

	}//<--- End Method

	/**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function update(Request $request)
	{
    $image = Images::findOrFail($request->id);

	if( $image->user_id != Auth::user()->id ) {
		return redirect('/');
	}

	$input = $request->all();

	$input['tags'] = Helper::cleanStr($input['tags']);

	if(strlen($input['tags']) == 1) {
		return redirect()->back()
				->withErrors(trans('validation.required', ['attribute' => trans('misc.tags')]));
	}

	if ($image->item_for_sale == 'sale' || $request->item_for_sale == 'sale') {
		$input['item_for_sale'] = 'sale';
	} else {
		$input['item_for_sale'] = 'free';
	}

	     $validator = $this->validatorUpdate($input);

			 if ($validator->fails()) {
          return redirect()->back()
 						 ->withErrors($validator)
 						 ->withInput();
 					 }

    $image->fill($input)->save();

    \Session::flash('success_message', trans('admin.success_update'));

    return redirect('edit/photo/'.$image->id);

	}//<--- End Method


	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function destroy(Request $request)
	{
	  $image = Images::find($request->id);

	  if ($image->user_id != Auth::user()->id) {
			return redirect('/');
		}

		// Delete Notification
		$notifications = Notifications::where('destination',$request->id)
			->where('type', '2')
			->orWhere('destination',$request->id)
			->where('type', '3')
			->orWhere('destination',$request->id)
			->where('type', '4')
			->get();

		if(  isset( $notifications ) ){
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

		foreach($stocks as $stock) {

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

      return redirect(Auth::user()->username);

	}//<--- End Method

	public function download($token_id)
	{
		$type = $this->request->type;

		$image = Images::where('token_id', $token_id)->where('item_for_sale', 'free')->firstOrFail();

		if (isset($image)) {
			$getImage = Stock::where('images_id', $image->id)->where('type','=', $type)->firstOrFail();
		}

		if (isset($getImage)) {

			// Download Check User
			$user_IP = request()->ip();
			$date = time();

			if (Auth::check()) {

			$downloadCheckUser = $image->downloads()->where('user_id', Auth::user()->id)->first();
			$dailyDownloads    = auth()->user()->downloads()->whereRaw("DATE(date) = '". date('Y-m-d', strtotime('today')) ."'")->whereType('free')->count();

			if (! $downloadCheckUser
					&& $this->settings->daily_limit_downloads != 0
					&& $dailyDownloads == $this->settings->daily_limit_downloads
					&& Auth::user()->id != $image->user()->id)
					{
						return back()->withPurchaseNotAllowed(trans('misc.reached_daily_download'));
					}

				if (! $downloadCheckUser && Auth::user()->id != $image->user()->id) {
							$download            = new Downloads;
							$download->images_id = $image->id;
							$download->user_id   = Auth::user()->id;
							$download->ip        = $user_IP;
							$download->type      = 'free';
							$download->size      = $type;
							$download->save();
				}
			}// Auth check

			else {

				// IF YOU SELECT "UNREGISTERED" ALREADY DOWNLOAD THE IMAGE
				$downloadCheckUser = $image->downloads()->where('user_id', 0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if ($downloadCheckUser)	{
				  $dateGuest = strtotime($downloadCheckUser->date) + (7200); // 2 Hours
			}

				if (empty( $downloadCheckUser->ip)) {
				   	$download            = new Downloads;
						$download->images_id = $image->id;
						$download->user_id   = 0;
						$download->ip        = $user_IP;
						$download->save();
			   } else if ($dateGuest < $date) {
			   		$download            = new Downloads;
						$download->images_id = $image->id;
						$download->user_id   = 0;
						$download->ip        = $user_IP;
						$download->save();
			   }

			}//<--------- * Visits * ---------->
			//<<<<---/ Download Check User

			if ($type != 'vector') {
					$pathFile = config('path.uploads').$type.'/'.$getImage->name;
					$resolution = $getImage->resolution;
			} else {
					$pathFile = config('path.files').$getImage->name;
					$resolution = trans('misc.vector_graphic');
			}

			$headers = [
				'Content-Type:' => ' image/'.$image->extension,
				'Cache-Control' => 'no-cache, no-store, must-revalidate',
				'Pragma' => 'no-cache',
				'Expires' => '0'
			];

			return Storage::download($pathFile, $image->title.' - '.$resolution.'.'.$getImage->extension, $headers);
		}
	}//<--- End Method

	public function report(Request $request){

		$data = ImagesReported::firstOrNew(['user_id' => Auth::user()->id, 'image_id' => $request->id]);

		if ($data->exists) {
			\Session::flash('noty_error','error');
			return redirect()->back();
		} else {

			$data->reason = $request->reason;
			$data->save();
			\Session::flash('noty_success','success');
			return redirect()->back();
		}

	}//<--- End Method

	public function purchase($token_id)
	{
		$type = strtolower($this->request->type);
		$license = strtolower($this->request->license);
		$urlDashboardUser = url('user/dashboard/purchases');

		if (url()->previous() == $urlDashboardUser && !$this->request->downloadAgain) {
			abort(404);
		}

		$image = Images::where('token_id', $token_id)->firstOrFail();

		// Validate Licenses and Type
		$licensesArray = ['regular', 'extended'];
		$typeArray     = ['small', 'medium', 'large', 'vector'];

		// License
		if (! in_array($license, $licensesArray) && Auth::user()->id != $image->user()->id) {
			 abort(404);
		}

		// Type
		if (! in_array($type, $typeArray) && Auth::user()->id != $image->user()->id) {
			 abort(404);
		}

		if ($license == 'extended') {
			 $image->price = ($image->price*10);
		}

		switch($type) {
		case 'small':
			$priceItem   = $image->price;
			break;
		case 'medium':
			$priceItem   = ($image->price * 2);
			break;
		case 'large':
			$priceItem   = ($image->price * 3);
			break;
		case 'vector':
				$priceItem  = ($image->price * 4);
				break;
			}

		if ($this->settings->sell_option == 'off' && Auth::user()->id != $image->user()->id) {
			return back()->withPurchaseNotAllowed(trans('misc.purchase_not_allowed'));
		}

		if (isset($image)) {
			$getImage = Stock::where('images_id', $image->id)->where('type', '=', $type)->firstOrFail();
		}

		// Download image from the user's Dashboard
		if ($this->request->downloadAgain) {
			return $this->downloadAgain($image, $getImage);
		}

		if(isset($getImage)) {

			if(Auth::user()->funds < $priceItem && Auth::user()->id != $image->user()->id) {
				return back()->withErrorPurchase(trans('misc.not_enough_funds'));
			}

			// Verify Purchase of the User
			//$verifyPurchaseUser = $image->purchases()->where('user_id', Auth::user()->id)->where('type', '=', $type)->first();

			// Earnings Net Seller
			if ($image->user()->author_exclusive == 'yes') {
				$adminFee = $this->settings->fee_commission;
			} else {
				$adminFee = $this->settings->fee_commission_non_exclusive;
			}

			$earningNetSeller = $priceItem - ($priceItem * $adminFee / 100);

			// Earnings Net Admin
			$earningNetAdmin = ($priceItem - $earningNetSeller);

			if(Auth::user()->id != $image->user()->id) {

						$user_IP = request()->ip();

						$purchase                     = new Purchases;
						$purchase->images_id          = $image->id;
						$purchase->user_id            = Auth::user()->id;
						$purchase->price              = $priceItem;
						$purchase->earning_net_seller = $earningNetSeller;
						$purchase->earning_net_admin  = $earningNetAdmin;
						$purchase->type               = $type;
						$purchase->license            = $license;
						$purchase->order_id	          = substr(strtolower( md5( microtime() . mt_rand( 1000, 9999 ) ) ), 0, 15 );
						$purchase->purchase_code      = implode( '-', str_split( substr( strtolower( md5( time() . mt_rand( 1000, 9999 ) ) ), 0, 27 ), 5 ) );
						$purchase->save();

						// Download
						$download            = new Downloads;
						$download->images_id = $image->id;
						$download->user_id   = Auth::user()->id;
						$download->ip        = $user_IP;
						$download->type      = $image->item_for_sale;
						$download->save();

						// Send Notification //destination, author, type, target
							Notifications::send($image->user()->id, Auth::user()->id, '5', $image->id);

						//Subtract user funds
						User::find(Auth::user()->id)->decrement('funds', $priceItem);

						//Add user balance
						User::find($image->user()->id)->increment('balance', $earningNetSeller);
			}
			//<<<<---/  Verify Purchase of the User

			if ($type != 'vector') {
				$pathFile = config('path.uploads').$type.'/'.$getImage->name;
				$resolution = $getImage->resolution;
			} else {
				$pathFile = config('path.files').$getImage->name;
				$resolution = trans('misc.vector_graphic');
			}

			$headers = [
				'Content-Type:' => ' image/'.$image->extension,
				'Cache-Control' => 'no-cache, no-store, must-revalidate',
				'Pragma' => 'no-cache',
				'Expires' => '0'
			];

			return Storage::download($pathFile, $image->title.' - '.$resolution.'.'.$getImage->extension, $headers);
		}// $getImage

	}//<--- End Method

	public function downloadAgain($image, $getImage)
	{

		$verifyPurchaseUserAgain = $image->purchases()
				->where('user_id', Auth::user()->id)
				->where('images_id', $image->id)
				->where('type', '=', $this->request->type)
				->where('license', '=', $this->request->license)
				->first();

				if (! $verifyPurchaseUserAgain) {
					abort(404);
				}

				if ($this->request->type != 'vector') {
					$pathFile = config('path.uploads').$this->request->type.'/'.$getImage->name;
					$resolution = $getImage->resolution;
				} else {
					$pathFile = config('path.files').$getImage->name;
					$resolution = trans('misc.vector_graphic');
				}

				$headers = [
					'Content-Type:' => ' image/'.$image->extension,
					'Cache-Control' => 'no-cache, no-store, must-revalidate',
					'Pragma' => 'no-cache',
					'Expires' => '0'
				];

				return Storage::download($pathFile, $image->title.' - '.$resolution.'.'.$getImage->extension, $headers);

	}//<--- End Method

	public function create()
	{
		if(Auth::guest()) {
		 return response()->json([
						 'session_null' => true,
						 'success' => false,
				 ]);
		}

		return $this->upload('normal');

	}

	public function image($size, $path)
	{
			try {

				$server = ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => Storage::disk()->getDriver(),
						'watermarks' => public_path('img'),
            'cache' => Storage::disk()->getDriver(),
						'source_path_prefix' => '/uploads/medium/',
            'cache_path_prefix' => '.cache',
            'base_url' => '/uploads/medium/',
        ]);

				if (request()->get('size') && request()->get('size') == 'small') {
					$thumbnail = true;
				} else {
					$thumbnail = false;
				}

				if (request()->get('size') && request()->get('size') == 'medium') {
					$medium = true;
				} else {
					$medium = false;
				}

				$resolution = explode('x', Helper::resolutionPreview($size, $thumbnail, $medium));

				$width = $resolution[0];
				$height = $resolution[1];

				$server->outputImage($path, [
					'w' => $width,
					'h' => $height,
					'mark' => $this->settings->show_watermark == 1 ? $this->settings->watermark : null,
					'markpos' => 'center',
					'markw' => '90w',
					''
				]
			);

				$server->deleteCache($path);

			} catch (\Exception $e) {

				abort(404);
				$server->deleteCache($path);
			}
    }

		public function preview($path)
		{
			$image = Stock::whereToken($path)->whereType('small')->select('name', 'resolution', 'extension')->firstOrFail();
			$resolution = $image->resolution;
			$resolution = explode('x', $image->resolution);
			$width = $resolution[0];
			$height = $resolution[1];

			$imageUrl = Storage::url(config('path.small').$image->name);

			header('Content-type: image/'.$image->extension);
			header('Cache-Control: public, max-age=10800');
			header("Expires: ".date('D, d F Y H:i:s', strtotime('+1 year')).""); // Fecha en el pasado

				// Crop Image
				if (request()->get('fit') == 'crop') {

					$size_x = 400;

					if ($width > $height) {
					    $new_height = $size_x;
					    $new_width = ($width / $height) * $new_height;

					    $x = ($width - $height) / 2;
					    $y = 0;
					} else {
				    $new_width = $size_x;
				    $new_height = ($height / $width) * $new_width;

				    $y = ($height - $width) / 2;
				    $x = 0;
					}

					$newImage = imagecreatetruecolor($size_x, $size_x);
				} else {

					switch(request()->get('w')) {
						case "tiny":
							$size_x = 100;
							break;
						case "small":
							$size_x = 280;
							break;
						case "medium":
							$size_x = 480;
							break;
						default:
								$size_x = 580;
				  }

					$size_y = 800;

					$resize_x = $size_x / $width;
					$resize_y = $size_y / $height;

					if ($resize_x < $resize_y) {
						$resize = $resize_x;
					} else {
						$resize = $resize_y;
					}

					$newImage = imagecreatetruecolor(ceil ($width * $resize), ceil( $height * $resize));
				}

				switch($image->extension) {
					case "gif":
						$source=imagecreatefromgif($imageUrl);
						imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
						imagealphablending( $newImage, TRUE );
						break;
				    case "pjpeg":
					case "jpeg":
					case "jpg":
						$source=imagecreatefromjpeg($imageUrl);
						break;
				    case "png":
						case "x-png":
						$source=imagecreatefrompng($imageUrl);
						imagealphablending( $newImage, false );
						imagesavealpha( $newImage, true );
						break;
			  }

				if (request()->get('fit') == 'crop') {
					imagecopyresampled($newImage, $source, 0, 0, $x, $y, $new_width, $new_height, $width, $height);
				} else {
					imagecopyresampled($newImage, $source,0,0,0,0, ceil($width * $resize), ceil($height * $resize), $width, $height);
				}

				switch($image->extension) {
					case "gif":
				  		imagegif($newImage);
						break;
			      	case "pjpeg":
							case "jpeg":
							case "jpg":
				  		imagejpeg($newImage, NULL, 90);
						break;
							case "png":
							case "x-png":
						imagepng($newImage);
						break;
			    }

				imagedestroy($newImage);
		}

}
