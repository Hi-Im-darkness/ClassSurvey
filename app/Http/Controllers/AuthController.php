<?php

namespace App\Http\Controllers;

use App\Models\Authenticator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Utils\ResponseWrapper;

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
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = array_values($request->only('username', 'password'));
        foreach (['admins', 'students', 'teachers'] as $provider)
        {
            $tmp = $credentials;
            array_push($tmp, $provider);
            if (! $user = $this->authenticator->attempt(...$tmp)) {
                continue;
            }

            $tokenResult = $user->createToken('Access Token');
            $token = $tokenResult->token;
            $token->save();

            $data = [
                'token_type' => 'Bearer',
                'access_token' => $tokenResult->accessToken,
                'name' => $user->name,
                'id' => $user->id,
                'provider' => $provider,
                'role-name' => $user->role_name,
                'permission' => $user->role()->pluck('permission')->toArray()
            ];
            return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
        }
        $data = 'Wrong username or password';
        return response()->json(ResponseWrapper::wrap(false, 401, 'reason', $data), 401);
    }

    public function logout(Request $request)
    {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if (!$user)
                continue;
            $user->token()->revoke();
            $data = 'Successfully logged out';
            return response()->json(ResponseWrapper::wrap(true, 200, 'message', $data));
        }
        $data = 'token invalid';
        return response()->json(ResponseWrapper::wrap(false, 400, 'reason', $data), 400);
    }
}
