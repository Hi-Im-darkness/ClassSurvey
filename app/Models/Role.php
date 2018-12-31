<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 11:01:30 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Role
 * 
 * @property string $id
 * @property string $permission
 * 
 * @property \Illuminate\Database\Eloquent\Collection $admins
 * @property \Illuminate\Database\Eloquent\Collection $students
 * @property \Illuminate\Database\Eloquent\Collection $teachers
 *
 * @package App\Models
 */
class Role extends Eloquent
{
	protected $table = 'role';
	public $incrementing = false;
	public $timestamps = false;

	public function admins()
	{
		return $this->hasMany(\App\Models\Admin::class, 'role_name');
	}

	public function students()
	{
		return $this->hasMany(\App\Models\Student::class, 'role_name');
	}

	public function teachers()
	{
		return $this->hasMany(\App\Models\Teacher::class, 'role_name');
	}
}
