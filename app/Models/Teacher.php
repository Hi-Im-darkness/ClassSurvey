<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 10:12:43 +0000.
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

/**
 * Class Teacher
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $role_name
 * 
 * @property \App\Models\Role $role
 * @property \Illuminate\Database\Eloquent\Collection $courses
 *
 * @package App\Models
 */
class Teacher extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;
	protected $table = 'teacher';
	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'email',
		'username',
		'password',
		'role_name'
	];

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class, 'role_name');
	}

	public function courses()
	{
		return $this->hasMany(\App\Models\Course::class);
	}
}
