<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TeacherImport;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Utils\ResponseWrapper;
use File;

class ExcelImportController extends Controller
{
    public function index() {
        return view('add-student');
    }

    public function studentImport(Request $request){
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

    public function teacherImport(Request $request){
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
                /* return redirect('/')->with('success', 'All good!'); */
                return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
            } else {
                /* Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'); */
                return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'file is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'));
            }
        }
    }

}
