<?php

namespace App;
use App\Models\User;
use App\Helper;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider )
    {
      $user = User::whereOauthProvider($provider)
          ->whereOauthUid($providerUser->getId())
          ->first();

      if (!$user) {
        //return 'Error! Your email is required, Go to app settings and delete our app and try again';
        if(!$providerUser->getEmail()) {
          return redirect("login")->with(array('login_required' => trans('error.error_required_mail')));
          exit;
        }

        //Verify Email user
        $userEmail = User::whereEmail($providerUser->getEmail())->first();

        if($userEmail) {
          return redirect("login")->with(array('login_required' => trans('error.mail_exists')));
          exit;
        }

        //$user = User::whereEmail($providerUser->getEmail())->first();

        $token = str_random(75);

        $avatar = 'default.jpg';
        $nameAvatar = time().$providerUser->getId();

        if(!empty($providerUser->getAvatar())){

          // Get Avatar Large Facebook
          if( $provider == 'facebook' ) {
            $avatarUser = str_replace('?type=normal', '?type=large', $providerUser->getAvatar());
          }

          // Get Avatar Large Twitter
          if( $provider == 'twitter' ) {
            $avatarUser = str_replace('_normal', '_200x200', $providerUser->getAvatar());
          }

                $fileContents = file_get_contents($avatarUser);

                \File::put(public_path().'/avatar/'.$nameAvatar.'.jpg', $fileContents);

                $avatar = $nameAvatar.'.jpg';
            }

				$user = User::create([
					'username'        => Helper::strRandom(),
					'name'        => $providerUser->getName(),
					'password'        => '',
					'email'           => strtolower($providerUser->getEmail()),
					'avatar'          => $avatar,
					'cover'           => 'cover.jpg',
					'status'          => 'active',
					'type_account'    => '1',
          'website' => '',
					'activation_code' => '',
          'oauth_uid' => $providerUser->getId(),
          'oauth_provider' => $provider,
					'token'           => $token,
			]);

        }// !$user
        return $user;
    }
}
