<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Dosurvey;
use App\Utils\ResponseWrapper;

class CourseController extends Controller
{
    public function showCourse(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if ($user->hasPermission('course-management')) {
            $data = [];
            foreach (Course::get() as $record) {
                $course_info = [
                    'id' => $record->id,
                    'course_code' => $record->course_code,
                    'course_name' => $record->name,
                    'teacher_name' => $record->teacher()->first()->name
                ];
                array_push($data, $course_info);
            }
            return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
        } else if ($guard == 'teacher') {
            $data = [];
            foreach ($user->courses()->get() as $record) {
                $course_info = [
                    'id' => $record->id,
                    'course_code' => $record->course_code,
                    'course_name' => $record->name,
                    'teacher_name' => $record->teacher()->first()->name
                ];
                array_push($data, $course_info);
            }
            return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
        } else {
            $data = [];
            foreach ($user->courses()->get() as $record) {
                $hasdone = False;
                if (Dosurvey::where([
                    ['student_id', $user->id],
                    ['survey_id', $record->surveys()->first()->id]
                ]) -> exists())
                    $hasdone = True;
                $course_info = [
                    'id' => $record->id,
                    'course_code' => $record->course_code,
                    'course_name' => $record->name,
                    'teacher_name' => $record->teacher()->first()->name,
                    'has_done' => $hasdone,
                ];
                array_push($data, $course_info);
            }
            return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
        }
    }
}
