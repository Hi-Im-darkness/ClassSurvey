<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Formquestion;
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
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $formid = $request->get('id');
        if ($formid) {
            $form = Form::find($formid);
            $question = $form->questions()->pluck('question.id')->toArray();
            $data = [
                'id' => $form->id,
                'name' => $form->name,
                'questions' => $question,
            ];
        } else {
            $data = Form::get(['id', 'name'])->toArray();
        }
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }

    public function addForm(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('name', 'questions'));
        $form = new Form([
            'name' => $in[0],
        ]);
        $form->save();
        
        foreach ($in[1] as $q) {
            $fq = new Formquestion([
                'form_id' => $form->id,
                'question_id' => $q,
            ]);
            $fq->save();
        }
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
            'name' => $in[0],
        ]));
    }

    public function deleteForm(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $formid = $request->get('id');
        Formquestion::where('form_id', $formid)->delete();
        Form::find($formid)->delete();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
    }

    public function editForm(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('form-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('id','name', 'questions'));
        Form::find($in[0])->update([
            'name' => $in[1],
        ]);
        Formquestion::where('form_id', $in[0])->delete();
        foreach ($in[2] as $q) {
            $fq = new Formquestion([
                'form_id' => $in[0],
                'question_id' => $q,
            ]);
            $fq->save();
        }
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
            'name' => $in[1],
        ]));
    }
}
