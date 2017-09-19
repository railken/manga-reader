<?php

namespace Api\Http\Controllers\Auth;

use Api\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\User\UserManager;
use Railken\Bag;
use Api\Exceptions\BadRequestException;
use Illuminate\Support\Collection;

class RegistrationController extends Controller
{

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
        $um = new UserManager();
        
        $params = new Bag($request->only(['username', 'password', 'email']));

        $errors = new Collection();

        // $errors = $errors->merge($um->required($params));
        $errors = $errors->merge($um->validate($params));
        // $errors = $errors->merge($um->uniqueness($params));

        if ($errors->count() !== 0)
            throw new BadRequestException($errors);

        $um->create($params);

        return $this->success([
            'message' => 'ok', 
            'code' => 'USER_REGISTERED'
        ]);
    }
}
