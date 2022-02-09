<?php

/*
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

/**
 * Class Controller
 * 
 * Base controller class
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Validate user login
     * 
     * @return void
     * @throws \Exception
     */
    protected function validateLogin()
    {
        try {
            if (\Auth::guest()) {
                throw new \Exception('Login required');
            }

            $user = User::getByAuthId();
            if ((!$user) || ($user->locked)) {
                throw new \Exception('Insufficient permissions');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate admin access
     * 
     * @return void
     * @throws \Exception
     */
    protected function validateAdmin()
    {
        try {
            if (\Auth::guest()) {
                throw new \Exception('Login required');
            }

            $user = User::getByAuthId();
            if ((!$user) || ($user->locked) || (!$user->admin)) {
                throw new \Exception('Insufficient permissions');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
