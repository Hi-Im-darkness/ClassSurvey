<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Utils\ResponseWrapper;

class QuestionController extends Controller
{
    public function showQuestion(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $data = Question::get()->toArray();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }

    public function showCategory(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
            'Cơ sỏ vật chất',
            'Môn học',
            'Hoạt động giảng dạy của giảng viên',
        ]));
    }

    public function addQuestion(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);
        
        $in = array_values($request->only('category', 'content'));
        $question = new Question([
            'category' => $in[0],
            'content' => $in[1],
        ]);
        $question->save();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $question->toArray()));
    }

    public function editQuestion(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('id', 'category', 'content'));
        $question = Question::find($in[0]);
        $question->update([
            'category' => $in[1],
            'content' => $in[2],
        ]);
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $question->toArray()));
    }

    public function deleteQuestion(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $questionid = $request->get('id');
        Question::find($questionid)->delete();

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
    }
}
