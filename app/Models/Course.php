<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Course
 * 
 * @property int $id
 * @property string $course_code
 * @property string $name
 * @property int $teacher_id
 * 
 * @property \App\Models\Teacher $teacher
 * @property \Illuminate\Database\Eloquent\Collection $students
 * @property \Illuminate\Database\Eloquent\Collection $surveys
 *
 * @package App\Models
 */
class Course extends Eloquent
{
	protected $table = 'course';
	public $timestamps = false;
    protected $hidden = ['pivot'];

	protected $casts = [
		'teacher_id' => 'int'
	];

	protected $fillable = [
		'course_code',
		'name',
		'teacher_id'
	];

	public function teacher()
	{
		return $this->belongsTo(\App\Models\Teacher::class);
	}

	public function students()
	{
		return $this->belongsToMany(\App\Models\Student::class, 'studentcourse')
					->withPivot('id');
	}

	public function surveys()
	{
		return $this->hasMany(\App\Models\Survey::class);
	}
}
