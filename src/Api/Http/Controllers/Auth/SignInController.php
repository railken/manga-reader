<?php

namespace Api\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Api\Http\Controllers\Controller;
use Core\User\UserManager;
use Api\OAuth\GithubProvider;
use Api\OAuth\GitlabProvider;

class SignInController extends Controller
{


    /**
     * List of all providers
     *
     * @var array
     */
    protected $providers = [
        'github' => GithubProvider::class,
        'gitlab' => GitlabProvider::class,
    ];

    /**
     * Get provider
     *
     * @return Provider
     */
    public function getProvider($name)
    {
        $class = isset($this->providers[$name]) ? $this->providers[$name] : null;

        if (!$class) {
            return null;
        }

        return new $class;
    }
    /**
     * Construct
     *
     * @param UserSerializer $user
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * Sign in a user
     *
     * @param Request $request
     *
     * @return Response
     */
    public function signIn(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        try {
            $oauth_client = \DB::table('oauth_clients')->where('password_client', 1)->first();

            $response = $client->request('POST', $request->getUriForPath('').'/api/v1/oauth/token', [
                'form_params' => [
                    'scope' => '*',
                    'grant_type' => 'password',
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'client_id' => $oauth_client->id,
                    'client_secret' => $oauth_client->secret,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->error(['message' => 'Credenziali errate']);
        }

        $body = json_decode($response->getBody());


        if (isset($body->access_token))
            return $this->success(['data' => $body]);


        return $this->error(['message' => $body->error]);

    }

    /**
     * Request token and generate a new one
     *
     * @param string $provider
     * @param Request $request
     *
     * @return Response
     */
    public function accessToken($provider, Request $request)
    {
        $provider = $this->getProvider($provider);

        if (!$provider) {
            return $this->error(['message' => 'No provider found']);
        }

        try {
            $response = $provider->issueAccessToken($request);
            $access_token = $response->access_token;

        } catch (\Exception $e) {
            return $this->error([
                'message' => 'Code invalid or expired'
            ]);
        } 

        return $this->success([
            'access_token' => $access_token,
            'provider' => $provider->getName(),
        ]);

    }

    /**
     * Serialize token
     *
     * @param Token $token
     *
     * @return array
     */
    public function serializeToken($token)
    {

        return [
            'access_token' => $token->accessToken,
            'token_type' => 'Bearer',
            'expire_in' => 0
        ];
    }

    /**
     * Request token and generate a new one
     *
     * @param string $provider
     * @param Request $request
     *
     * @return Response
     */
    public function exchangeToken($provider, Request $request)
    {
        $provider = $this->getProvider($provider);

        if (!$provider) {
            return $this->error(['message' => 'No provider found']);
        }

        $access_token = $request->input('access_token');

        if (!$access_token) {
            return $this->error([
                "message" => "access_token is missing"
            ]);
        }

        try {
            $provider_user = $provider->getUser($access_token);
        } catch (\Exception $e) {
            return $this->error([
                'message' => 'Token invalid or expired'
            ]);
        } 

        $user = $this->manager->getRepository()->findOneByEmail($provider_user->email);

        if (!$user) {
            $result = $this->manager->create([
                'username' => $provider_user->username,
                'role' => 'user',
                'password' => sha1(str_random()),
                'avatar' => $provider_user->avatar,
                'email' => $provider_user->email
            ]);

            if (!$result->ok()) {
                return $this->error(['errors' => $result->getSimpleErrors()]);
            }

            $user = $result->getResource();
        }

        $token = $user->createToken('login');

        return $this->success($this->serializeToken($token));
    }

}
