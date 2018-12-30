<?php

namespace App\Imports;

use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeacherImport implements ToModel
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
        return new Teacher([
            'username' => $row[1],
            'password' => Hash::make($row[2]),
            'name' => $row[3],
            'email' => $row[4],
        ]);
    }
}
