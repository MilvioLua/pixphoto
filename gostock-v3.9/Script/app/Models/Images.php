<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\Search;

class Images extends Model
{

	use Search;

	protected $guarded = array();
	public $timestamps = false;

	protected $fillable = [
        'title', 'description', 'categories_id', 'tags','camera','exif', 'how_use_image', 'attribution_required', 'price', 'item_for_sale'
    ];

		protected $searchable = [
	        'title',
	        'tags'
	    ];

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

	public function likes(){
		return $this->hasMany('App\Models\Like')->where('status', '1');
	}

	public function downloads(){
		return $this->hasMany('App\Models\Downloads');
	}

	public function stock(){
		return $this->hasMany('App\Models\Stock')->orderBy('type','asc');
	}

	 public function comments(){
		return $this->hasMany('App\Models\Comments');
	}

	 public function visits(){
		return $this->hasMany('App\Models\Visits');
	}

	 public function category() {
	 	 return $this->belongsTo('App\Models\Categories', 'categories_id');
	 }

	 public function collections() {
	 	 return $this->belongsTo('App\Models\Collections');
	 }

	 public function collectionsImages() {
	 	 return $this->belongsTo('App\Models\CollectionsImages');
	 }

	  public function tags() {
	 	 return $this->hasMany('App\Models\Images', 'tags');
	 }

	 public function purchases(){
 		return $this->hasMany('App\Models\Purchases');
 	}
}
