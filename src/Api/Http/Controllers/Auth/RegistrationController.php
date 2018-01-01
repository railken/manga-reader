<?php

namespace Api\Http\Controllers\Auth;

use Api\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\User\UserService;
use Railken\Bag;
use Api\Exceptions\BadRequestException;
use Illuminate\Support\Collection;

class RegistrationController extends Controller
{

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
     * Register a user
     *
     * @param Request $request
     *
     * @error
     *
     * @return response
     */
    public function index(Request $request)
    {
        $um = new UserService();

        $result = $um->register($request->only(['username', 'password', 'email']));

        $errors = $result->getErrors();

        if ($errors->count() !== 0) {
            throw new BadRequestException($errors);
        }

        $user = $result->getResource();

        return $this->success([
            'code' => 'USER_REGISTERED',
            'message' => 'ok'
        ]);
    }

    /**
     * Confirm email
     *
     * @param Request $request
     *
     * @return Response
     */
    public function confirmEmail(Request $request)
    {
        $um = new UserService();
            
        $user = $um->confirmEmail($request->input('token'));

        
        if (!$user) {
            return $this->error([
                'code' => 'SIGNUP.CONFIRM_EMAIL_TOKEN_INVALID',
                'message' => "Token invalid"
            ]);
        }

        $token = $user->createToken('login');

        return $this->success($this->serializeToken($token));

    }

    /**
     * Request Confirm email
     *
     * @param Request $request
     *
     * @return Response
     */
    public function requestConfirmEmail(Request $request)
    {
        $um = new UserService();
            
        $user = $um->manager->repository->findOneByEmail($request->input('email'));

        if ($user && !$user->enabled) {
            $um->requestConfirmEmail($user);
        }

        return $this->success(['message' => 'ok']);

    }
}
