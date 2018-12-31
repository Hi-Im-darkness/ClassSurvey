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
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                Excel::import(new StudentImport, $request->file);
                return response()->json(ResponseWrapper::wrap(true, 200, 'message', 'import success'));
            } else {
                return response()->json(ResponseWrapper::wrap(false, 400, 'error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'));
            }
        }
    }

    public function teacherImport(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                Excel::import(new TeacherImport, $request->file);
                /* return redirect('/')->with('success', 'All good!'); */
                return response()->json(ResponseWrapper::wrap(true, 200, 'message', 'import success'));
            } else {
                /* Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'); */
                return response()->json(ResponseWrapper::wrap(false, 400, 'error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!'));
            }
        }
    }

}
