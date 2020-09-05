<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Comments;
use App\Models\CommentsLikes;
use App\Models\Notifications;
use App\Models\Images;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CommentsController extends Controller {

	 public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

	 protected function validator(array $data) {

    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

			return Validator::make($data, [
	        	'comment' =>  'required|max:'.$this->settings->comment_length.'|min:2',
	        ]);

    }

	 /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
	 public function store( Request $request ) {

		 $input = $request->all();

	     $validator = $this->validator($input);

	   $image = Images::where('id',$request->image_id)->where('status','active')->first();

	   if( !isset($image) ) {
	   		return response()->json([
			        'success' => false,
			        'error_image' => trans('misc.error'),
			    ]);
				exit;
	   }

	    if ($validator->fails()) {
	        return response()->json([
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			    ]);
	    }

		$sql            = new Comments;
		$sql->reply     = trim(Helper::checkTextDb($request->comment));
		$sql->images_id = $request->image_id;
		$sql->user_id   = Auth::user()->id;
		$sql->save();

		$idComment = $sql->id;

		/*------* SEND NOTIFICATION * ------*/

		if( Auth::user()->id != $image->user_id ) {
			// Send Notification //destination, author, type, target
			Notifications::send( $image->user_id, Auth::user()->id, '3', $image->id );
		}

		if(Auth::user()->name != '') {
			$nameUser = Auth::user()->name;
		} else {
			$nameUser = Auth::user()->username;
		}

		return response()->json([
			        'success' => true,
			        'total' => number_format( $image->comments()->count()),
			        'data' => '<div class="col-thumb">
	      <div class="media media-comments position-relative" data-id="'.$idComment.'">
			<span class="pull-left">
				<a href="'.url(Auth::user()->username).'">
				<img width="50" height="50" class="media-object img-circle" src="'.Storage::url(config('path.avatar').Auth::user()->avatar).'">
			</a>
			</span>
			<div class="media-body media-body-comments">
				<h4 class="media-heading col-thumb">
					<a class="text-decoration-none class-montserrat" href="'.url(Auth::user()->username).'">'.$nameUser.'</a>
						</h4>
				<p class="comments-p mentions-links">'.Helper::checkText($sql->reply).'</p>
				<div class="btn-block margin-top-5">
					<small class="timeAgo small-comment" data="'.date('c', time()).'"></small>

					<span class="likeComment" data-id="'.$idComment.'">
					<i class="fa fa-heart-o myicon-right"></i>
					</span>

					<span class="deleteComment" data-id="'.$idComment.'" title="'.trans('misc.delete').'">
					<i class="fa fa-trash-o"></i>
					</span>

	<small data-id="'.$idComment.'" data-toggle="collapse" href="#collapse'.$idComment.'" aria-expanded="false" style="cursor:pointer;" class="pull-right comments-likes like-small display-none">
		<span class="fa fa-heart myicon-right"></span>
		<span class="count">0</span>
	</small>

    			</div>
    		</div>
  		 </div><!-- media -->

<ul class="list-inline collapse text-right" id="collapse'.$idComment.'">
	   	<li>'.trans('misc.loading').'</li>
	 </ul>

	</div><!-- media box -->',
			    ]);


	}//<--- End Method

	public function like(Request $request) {

		$id = $request->comment_id;

	    $comment = Comments::where('id', $id)->where('status','1')->first();

		$comment_like = CommentsLikes::where('user_id', '=', Auth::user()->id)
		->where('comment_id', '=', $id)->first();

		if( isset( $comment_like->id ) ){

			if( $comment_like->status == '1' ) {
				//UNLIKE
				$comment_like->status = '0';
				$comment_like->save();

				$comment_count = CommentsLikes::where('comment_id', '=', $id)->where('status','1')->count();

				return response()->json( array ( 'success' => true, 'type' => 'unlike', 'count' => $comment_count ) );
			} else {
				//UNLIKE
				$comment_like->status = '1';
				$comment_like->save();

				$comment_count = CommentsLikes::where('comment_id', '=', $id)->where('status','1')->count();

				return response()->json( array ( 'success' => true, 'type' => 'like', 'count' => $comment_count ) );
			}

		} else {
			$like                      = new CommentsLikes;
			$like->user_id        = Auth::user()->id;
			$like->comment_id = $id;
			$like->save();

			// SEND NOTIFICATION
			if( Auth::user()->id != $comment->user_id ) {
				Notifications::send( $comment->user_id, Auth::user()->id, '4', $comment->images_id );
			}

			$comment_count = CommentsLikes::where('comment_id', '=', $id)->where('status','1')->count();

			return response()->json( array ( 'success' => true, 'type' => 'like', 'count' => $comment_count ) );
		}

	}//<--- END METHOD

	public function getLikes(Request $request) {

				$comment_id = $request->comment_id;

				$_array   = array();

				$data = CommentsLikes::where('comment_id', $comment_id)->where('status', '1')->get();

				if( !isset( $data ) ){
					return false;
					exit;
				}

				foreach ($data as $key) {
					$_array[] = '<li><a href="'.url($key->user()->username).'" class="showTooltip" data-toggle="tooltip" data-placement="left" title="'.$key->user()->username.'">
					<img src="'.Storage::url(config('path.avatar').$key->user()->avatar).'" class="img-circle" width="25">
					</a></li>';
				}
				return $_array;

		}//<--- END METHOD


	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function destroy(Request $request){

	  $comment_id = $request->comment_id;
		$data = Comments::where('id',$comment_id)
		->where('user_id', Auth::user()->id)
		->first();

		if( isset( $data ) ) {

			// Delete Notification
			Notifications::where('author',Auth::user()->id)
			->where('target', $data->images_id)
			->where('created_at', $data->date)
			->update(array('trash' => '1', 'status' => '1' ));

			$data->delete();

			return response()->json( array( 'success' => true ) );

		} else {
			return response()->json( array( 'success' => false, 'error' => trans('misc.error') ) );
		}

	}//<--- End Method


}
