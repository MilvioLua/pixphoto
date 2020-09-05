<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionsImages extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

	public function images() {
        return $this->belongsTo('App\Models\Images')->where('status','active')->orderBy('id','desc')->first();
    }

	public function collection() {
        return $this->belongsTo('App\Models\Collections')->first();
    }

	public function belongsCollection() {
        return $this->belongsTo('App\Models\Collections', 'collections_id')->orderBy('id','desc');
    }

	public function collections() {
        return $this->hasMany('App\Models\Collections')->orderBy('id','desc');
    }

	public function likes(){
		return $this->hasMany('App\Models\Like', 'images_id','images_id')->where('status', '1');
	}

	public function downloads(){
		return $this->hasMany('App\Models\Downloads','images_id','images_id');
	}

}
