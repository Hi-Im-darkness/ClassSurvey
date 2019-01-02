<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseWrapper;
use App\Models\Course;
use App\Models\Survey;
use App\Models\Form;
use App\Models\Question;
use App\Models\Dosurvey;

class SurveyController extends Controller
{
    public function showCourses(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'token invalid'), 401);

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
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'token invalid'), 401);

        if (! $user->hasPermission('do-survey'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'unauthorized'), 401);


        $courseid =  $request->get('courseid');
        $id = Survey::where('course_id', $courseid)->value('id');
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
            'survey_id' => $id,
            'name' => $name,
            'form' => $data
        ];
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }

    public function doSurvey(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'token invalid'), 400);

        if (! $user->hasPermission('do-survey'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'unauthorized'), 401);

        $survey_id = $request->get('survey_id');
        $ans_arr = $request->get('answers');
        foreach ($ans_arr as $question_id => $ans) {
            $dosurvey = new Dosurvey([
                'student_id' => $user->value('id'),
                'survey_id' => $survey_id,
                'question_id' => $question_id,
                'answer' => $ans
            ]);
            $dosurvey->save();
        }
        return response()->json(ResponseWrapper::wrap(true, 200, 'message', 'do survey successfully'));
    }
}
