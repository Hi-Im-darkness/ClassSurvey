<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentImport implements ToModel, WithStartRow
{
    public function startRow(): int {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'username' => $row[1],
            'password' => Hash::make($row[2]),
            'name' => $row[3],
            'email' => $row[4],
            'class' => $row[5],
            'role_name' => 'STUDENT',
        ]);
    }
}
