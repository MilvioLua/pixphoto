<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Collections;
use App\Models\CollectionsImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller {
	
	
	 protected function validator(array $data) {
	 	
    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});
				

			return Validator::make($data, [
	        	'title' =>  'required|max:25|min:2',
	        ]);
		
    }
	 
	 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
	 public function index() {
	 	
	 }
	 
	 /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
	 public function store( Request $request ) {
		
		 $input = $request->all();
		 
	     $validator = $this->validator($input);
	
	    if ($validator->fails()) {
	        return response()->json([
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			    ]);
	    }
		
		$sql          = new Collections;
		$sql->title   = trim($request->title);
		$sql->type    = $request->type;
		$sql->user_id = Auth::user()->id;
		$sql->save();
		
		$idCollection = $sql->id;
		
		return response()->json([
			        'success' => true,
			        'data' => '<div class="radio margin-bottom-15">
							<label class="checkbox-inline addImageCollection text-overflow padding-zero" data-image-id="'.$request->image_id.'" data-collection-id="'.$idCollection.'">
							<input class="no-show addListUser" name="checked" type="checkbox" value="true">
							<span class="input-sm">'.trim(e($request->title)).'</span>
							</label>
						</div>',
			    ]);

		
	}//<--- End Method
   
    // Add Remove Image to Collection
    public function addImageCollection(Request $request){
    	
		if($request->ajax()) {
			
			$collectionsImage = CollectionsImages::where(['collections_id' => $request->id, 'images_id' => $request->image])->first();
			
	    // Verify user
		if( isset( $collectionsImage ) && $collectionsImage->belongsCollection->user_id != Auth::user()->id ) {
			return response()->json([
				        'status' => false,
				        'error' => trans('misc.error'),
				    ]); 
					exit;
		}// <--- Verify user
			
					
			if( isset( $collectionsImage ) ) {
				$collectionsImage->delete();
				
				return response()->json([
				        'status' => true,
				        'data' => trans('misc.successfully_removed'),
				    ]); 
				    
			} else {
				$saveCollectionsImage = new CollectionsImages;
				$saveCollectionsImage->collections_id = $request->id;
				$saveCollectionsImage->images_id = $request->image;
				$saveCollectionsImage->save();
				return response()->json([
				        'status' => true,
				        'data' => trans('misc.successfully_added'),
				    ]); 
			}
		}
    }
	
	// Edit Collection
	 public function edit( Request $request ) {
		
		 $input = $request->all();
		 
		 $sql  = Collections::find($request->id);
		 
		 if( !isset($sql) || $sql->user_id != Auth::user()->id ) {
		 	return response()->json([
			        'not_authorized' => true,
			    ]);
		 }
		 
	     $validator = $this->validator($input);
	
	    if ($validator->fails()) {
	        return response()->json([
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			    ]);
	    }
		
		$sql->title  = trim($request->title);
		$sql->type = $request->type;
		$sql->save();
		
		$idCollection = $sql->id;
		
		return response()->json([
			        'success' => true,
			    ]);

		
	}//<--- End Method
		
	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function destroy($id){

	  $collection = Collections::findOrFail($id);
	  $collectionsImages = CollectionsImages::find($collection->collections_id);
	  	  
	  if( !isset($collection) || $collection->user_id != Auth::user()->id ) {
		 	return redirect()->back();
		 }
	  
	  if( isset( $collectionsImages ) ) {
	  	foreach( $collectionsImages as $collectionsImage ) {
	  		$collectionsImage->delete();
	  	}
	  }

      $collection->delete();

      return redirect(Auth::user()->username.'/collections');
	  
	}//<--- End Method


}
