<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replies extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

	public function comment() {
        return $this->hasMany('App\Models\Comment');
    }

}
