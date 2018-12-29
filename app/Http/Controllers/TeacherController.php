<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;
use File;

class TeacherController extends Controller
{
    public function import(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                Excel::import(new TeacherImport, $request->file);
                return redirect('/')->with('success', 'All good!');
            } else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }
}
