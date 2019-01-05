<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Dosurvey;
use App\Models\Studentcourse;
use App\Utils\ResponseWrapper;
use App\Imports\CoursesImport;
use Maatwebsite\Excel\Facades\Excel;
use File;

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

    public function import(Request $request){
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('course-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);
        
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                $data = Excel::import(new CoursesImport, $request->file);
                return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
            } else {
                return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'file is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'));
            }
        }
    }

    public function listStudent(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $courseid = $request->get('id');
        if (! $user->hasPermission('course-management'))
            if (! $user->courses()->where('course_id', $courseid)-> exists())
                return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);
        $course = Course::find($courseid);
        $students = $course->students()->get(['student.id', 'name', 'email', 'class'])->toArray();
        $data = [
            'course_id' => $courseid,
            'course_code' => $course->course_code,
            'course_name' => $course->name,
            'students' => $students
        ];
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }
}
