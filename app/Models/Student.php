<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Student
 * 
 * @property int $id
 * @property string $student_code
 * @property string $name
 * @property string $email
 * @property string $class
 * @property string $password
 * @property string $remember_token
 * 
 * @property \Illuminate\Database\Eloquent\Collection $dosurveys
 * @property \Illuminate\Database\Eloquent\Collection $courses
 *
 * @package App\Models
 */
class Student extends Eloquent
{
	protected $table = 'student';
	public $timestamps = false;

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'student_code',
		'name',
		'email',
		'class',
		'password',
	];

	public function dosurveys()
	{
		return $this->hasMany(\App\Models\Dosurvey::class);
	}

	public function courses()
	{
		return $this->belongsToMany(\App\Models\Course::class, 'studentcourse')
					->withPivot('id');
	}
}
