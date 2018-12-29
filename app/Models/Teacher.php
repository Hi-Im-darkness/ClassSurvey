<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Teacher
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $user_name
 * @property string $password
 * @property string $remember_token
 * 
 * @property \Illuminate\Database\Eloquent\Collection $courses
 *
 * @package App\Models
 */
class Teacher extends Eloquent
{
	protected $table = 'teacher';
	public $timestamps = false;

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'user_name',
		'password',
	];

	public function courses()
	{
		return $this->hasMany(\App\Models\Course::class);
	}
}
