<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
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
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'token invalid'), 401);

        if ($user->hasPermission('course-management')) {
            $data = [];
            foreach (Course::get() as $record) {
                $course_info = [
                    'id' => $record->name,
                    'course_code' => $record->value('course_code'),
                    'course_name' => $record->name,
                    'teacher_name' => $record->teacher()->value('name')
                ];
                array_push($data, $course_info);
            }
            return response()->json(ResponseWrapper::wrap(true, 200, 'courses', $data));
        } else {
            $data = [];
            foreach ($user->courses()->get() as $record) {
                $course_info = [
                    'id' => $record->name,
                    'course_code' => $record->value('course_code'),
                    'course_name' => $record->name,
                    'teacher_name' => $record->teacher()->value('name')
                ];
                array_push($data, $course_info);
            }
            return response()->json(ResponseWrapper::wrap(true, 200, 'courses', $data));
        }
    }
}
