<?php

namespace Api\OAuth;

use League\OAuth2\Server\Util\SecureKey;

class Token
{

    /**
     * Generate or retrieve the access token given user
     *
     * @param User $user
     *
     * @return string
     */
    public static function firstOrCreate($user, $scope = '', $expire = 3600000)
    {
        if (!$user) {
            return null;
        }

        $scope; # Maybe one day.

        $client = OAuthClient::first();

        $params = [
            'owner_type' => 'user',
            'owner_id' => $user->id,
            'client_id' => $client->id
        ];


        $session = OAuthSession::where($params)->first();


        if ($session && $session->access_token && ($session->access_token->expire_time > time())) {

            return $session->access_token;
        }


        if ($session && $session->access_token) {
            $session->access_token->delete();
        }

        $session = $session ? $session : OAuthSession::create($params);


        $access_token = OAuthAccessToken::create([
            'id' =>  SecureKey::generate(),
            'client_id' => $client->id,
            'expire_time' => $expire + time(),
            'session_id' => $session->id,
        ]);

        $session->save();
        $access_token->save();

        return OAuthSession::where($params)->first()->access_token;
    }
}
