<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Models\Student;
use App\Utils\ResponseWrapper;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use File;

class StudentController extends Controller
{
    public function index() {
        return view('add-student');
    }

    public function import(Request $request){
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('student-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);
        
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                Excel::import(new StudentImport, $request->file);
                return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
            } else {
                return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'file is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'));
            }
        }
    }

    public function showStudent(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('student-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $data = Student::get(['id', 'username', 'name', 'email', 'class'])->toArray();

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }

    public function addStudent(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('student-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('username', 'name', 'email', 'password', 'class'));
        $student = new Student([
            'username' => $in[0],
            'name' => $in[1],
            'email' => $in[2],
            'password' => Hash::make($in[3]),
            'class' => $in[4],
            'role_name' => 'STUDENT',
        ]);
        $student->save();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
                                                    'id' => $student->id,
                                                    'username' => $student->username,
                                                    'name' => $student->name,
                                                    'email' => $student->email,
                                                    'class' => $student->class,
                                                ]));
    }

    public function editStudent(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('student-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('id', 'username', 'name', 'email', 'class', 'password'));
        $student = Student::find($in[0]);
        $student->update([
            'username' => $in[1],
            'name' => $in[2],
            'email' => $in[3],
            'class' => $in[4],
        ]);
        if (array_key_exists(5, $in))
            $student->update([
                'password' => Hash::make($in[5]),
            ]);
        $student->save();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
                                                    'id' => $student->id,
                                                    'username' => $student->username,
                                                    'name' => $student->name,
                                                    'email' => $student->email,
                                                    'class' => $student->class,
                                                ]));
    }

    public function deleteStudent(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('student-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $student_id = $request->get('id');
        Student::find($student_id)->delete();

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
    }
}
