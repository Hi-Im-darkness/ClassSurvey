<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Utils\ResponseWrapper;

class FormController extends Controller
{
    public function showForm(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'token invalid'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'unauthorized'), 401);

        $data = Form::get(['id', 'name'])->toArray();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }
}
