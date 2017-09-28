<?php

namespace Api\OAuth;

class GoogleProvider extends Provider
{

 /**
     * Name
     *
     * @var string
     */
    protected $name = 'google';

    /**
     * URL
     *
     * @var string
     */
    protected $url = 'https://www.googleapis.com/oauth2/v2';


    /**
     * Construct
     *
     */
    public function __construct()
    {

    }

    /**
     * Issue access token
     *
     * @return array
     */
    public function getAccessToken($request)
    {

        $client = new \GuzzleHttp\Client();

        try {
            $params =  [
                'query' => [
                    'client_id' => $request->input('client_id'),
                    'client_secret' => $request->input('client_secret'),
                    'redirect_uri' => $request->input('redirect_uri'),
                    'code' => $request->input('code')
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ];

            $response = $client->request('GET', $this->url."/oauth/access_token", $params);
        } catch (\Exception $e) {
            throw $e;
        }

        $body = json_decode($response->getBody());

        return $body;
    }



    /**
     * Retrieve User
     *
     * @return array
     */
    public function getUser($token)
    {
        $client = new \GuzzleHttp\Client();
        $user = new \stdClass;

        try {
            $response = $client->request('GET', $this->url."/userinfo", [
                'query' => [
                    'access_token' => $token
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "token {$token}"
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody());
        } catch (\Exception $e) {
            throw $e;
        }


        $user->firstname = explode(" ",$body->name)[0];
        $user->lastname = explode(" ",$body->name)[1];
        $user->email = $body->email;
        $user->id = $body->id;
        $user->avatar = $body->picture;

        return $user;
    }}