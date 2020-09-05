<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

	public function collection_images() {
        return $this->hasMany('App\Models\CollectionsImages')->orderBy('id','desc');
    }



}
