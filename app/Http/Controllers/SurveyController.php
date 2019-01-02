<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseWrapper;
use App\Models\Course;
use App\Models\Survey;
use App\Models\Form;
use App\Models\Question;

class SurveyController extends Controller
{
    public function showCourses(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'token invalid'), 400);

        $courses = $user->courses()->with([
            'teacher' => function($query) { $query->select('id', 'name'); }
            ])->get()->toArray();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $courses));
    }

    public function showSurveyForm(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'token invalid'), 400);

        if (! $user->hasPermission('do-survey'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'unauthorized'), 401);


        $courseid =  $request->get('courseid');
        $name = Survey::where('course_id', $courseid)->value('name');
        $form = Survey::where('course_id', $courseid)->first()->form()->first();
        $question = $form->questions(); 
        $data = [];
        foreach ($question->distinct('category')->pluck('category')->toArray() as $cat) {
            array_push($data, [
                'category' => $cat,
                'questions' => Question::where('category', $cat)->get(['id', 'content'])->toArray()
            ]);
        }
        $data = [
            'name' => $name,
            'form' => $data
        ];
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }
}
