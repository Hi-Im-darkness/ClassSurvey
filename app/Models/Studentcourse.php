<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Studentcourse
 * 
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * 
 * @property \App\Models\Student $student
 * @property \App\Models\Course $course
 *
 * @package App\Models
 */
class Studentcourse extends Eloquent
{
	protected $table = 'studentcourse';
	public $timestamps = false;

	protected $casts = [
		'student_id' => 'int',
		'course_id' => 'int'
	];

	protected $fillable = [
		'student_id',
		'course_id'
	];

	public function student()
	{
		return $this->belongsTo(\App\Models\Student::class);
	}

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class);
	}
}
