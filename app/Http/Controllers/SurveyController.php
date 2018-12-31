<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseWrapper;
use App\Models\Course;
use App\Models\Survey;

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

        /* $courses = $user->courses()->get(['course_id', 'course_code', 'name'])->toArray(); */
        /* $courses = $user->courses()->with('Teacher.name')->get(['course_id', 'course_code', 'name', 'teacher_id'])->toArray(); */
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

        $courseid =  $request->get('courseid');
        $name = Survey::where('course_id', $courseid)->value('name');
        echo $name;

    }
}
