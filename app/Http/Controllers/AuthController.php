<?php

namespace App\Http\Controllers;

use App\Models\Authenticator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function showLogin()
    {
        // show the form
        return view('login');
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthenticationException
     */
    public function login(Request $request)
    {
        $credentials = array_values($request->only('username', 'password'));
        foreach (['admins', 'students', 'teachers'] as $provider)
        {
            $tmp = $credentials;
            array_push($tmp, $provider);
            if (! $user = $this->authenticator->attempt(...$tmp)) {
                continue;
            }

            $token = $user->createToken($provider)->accessToken;

            $res = [
                'token_type' => 'Bearer',
                'access_token' => $token,
            ];
            echo $res;
            return $res;
        }
        echo 'login fail';
    }
}
