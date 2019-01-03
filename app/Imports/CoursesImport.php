<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Studentcourse;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Utils\ResponseWrapper;

class CoursesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $teacher_id = $rows[6][4];
        $course_code = $rows[8][2];
        $course_name = $rows[9][2];
        $course = new Course([
            'course_code' => $course_code,
            'name' => $course_name,
            'teacher_id' => $teacher_id
        ]);
        $course->save();
        $course_id = $course->id;
        foreach ($rows as $id => $row) 
        {
            if ($id < 11)
                continue;
            $student_code = $row[1];
            if (! $student_code)
                break;
            $student_id = Student::where('username', $student_code)->first()->id;
            $stu_cou = new Studentcourse([
                'student_id' => $student_id,
                'course_id' => $course_id,
            ]);
            $stu_cou->save();
        }
    }
}
