<?php

namespace Api\Http\Controllers\User;

use Api\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\User\UserManager;

class AccountController extends Controller
{

    /**
     * Construct
     *
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Change user password
     *
     * @param Request $request
     *
     * @return response
     */
    public function password(Request $request)
    {
       
        $result = $this->manager->changePassword($this->getUser(), $request->input('password_old'), $request->input('password'));

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        return $this->success();
    }
}
