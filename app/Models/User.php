<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
	use Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;

    protected $fillable = [
        'username',
				'name',
				'bio',
				'email',
				'password',
				'avatar',
				'cover',
				'status',
				'type_account',
				'website',
				'twitter',
				'paypal_account',
				'activation_code',
				'oauth_uid',
				'oauth_provider',
				'token',
				'authorized_to_upload',
				'role',
				'ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

		public function sendPasswordResetNotification($token) {

        $this->notify(new ResetPasswordNotification($token));
    }

	public function images() {
        return $this->hasMany('App\Models\Images')->where('status','active');
    }

	public function images_pending() {
        return $this->hasMany('App\Models\Images')->where('status','pending');
    }

	public function collections() {
        return $this->hasMany('App\Models\Collections')->where('type','public');
    }

	public function likes() {
        return $this->hasMany('App\Models\Like');
    }

	public function downloads() {
        return $this->hasMany('App\Models\Downloads');
    }

	public function comments() {
        return $this->hasMany('App\Models\Comments');
    }

	public function following() {
        return $this->hasMany('App\Models\Followers', 'follower')->where('status','1');
    }

	public function followers() {
        return $this->hasMany('App\Models\Followers', 'following')->where('status','1');
    }

	public function notifications() {
        return $this->hasMany('App\Models\Notifications', 'destination');
    }

	public function country() {
        return $this->belongsTo('App\Models\Countries', 'countries_id')->first();
    }

	public static function totalImages($id){
		return \App\Models\Images::where('user_id', '=', $id )->where('status','active')->count();
	}

	public function purchases() {
				return $this->hasMany('App\Models\Purchases');
		}


}
