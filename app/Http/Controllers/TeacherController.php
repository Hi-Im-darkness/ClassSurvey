<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Teacher;
use App\Utils\ResponseWrapper;
use Illuminate\Support\Facades\Hash;
use File;

class TeacherController extends Controller
{
    public function import(Request $request){
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('teacher-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                Excel::import(new TeacherImport, $request->file);
                return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
            } else {
                return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'file is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'));
            }
        }
    }

    public function showTeacher(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('teacher-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $data = Teacher::get(['id', 'username', 'name', 'email'])->toArray();

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }

    public function addTeacher(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('teacher-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('username', 'name', 'email', 'password'));
        $teacher = new Teacher([
            'username' => $in[0],
            'name' => $in[1],
            'email' => $in[2],
            'password' => Hash::make($in[3]),
            'role_name' => 'TEACHER',
        ]);
        $teacher->save();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
            'id' => $teacher->id,
            'username' => $teacher->username,
            'name' => $teacher->name,
            'email' => $teacher->email,
        ]));
    }

    public function editTeacher(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('teacher-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('id', 'username', 'name', 'email', 'password'));
        $teacher = Teacher::find($in[0]);
        $teacher->update([
            'username' => $in[1],
            'name' => $in[2],
            'email' => $in[3],
        ]);
        if ($in[4])
            $teacher->update([
                'password' => Hash::make($in[4]),
            ]);
        $teacher->save();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
            'id' => $teacher->id,
            'username' => $teacher->username,
            'name' => $teacher->name,
            'email' => $teacher->email,
        ]));
    }

    public function deleteTeacher(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('teacher-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $teacher_id = $request->get('id');
        Teacher::find($teacher_id)->delete();

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
    }
}
