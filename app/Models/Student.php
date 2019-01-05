<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 10:18:05 +0000.
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

/**
 * Class Student
 * 
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $class
 * @property string $password
 * @property string $role_name
 * 
 * @property \App\Models\Role $role
 * @property \Illuminate\Database\Eloquent\Collection $dosurveys
 * @property \Illuminate\Database\Eloquent\Collection $courses
 *
 * @package App\Models
 */
class Student extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;
	protected $table = 'student';
	public $timestamps = false;

	protected $hidden = [
        'password',
        'pivot'
	];

	protected $fillable = [
		'username',
		'name',
		'email',
		'class',
		'password',
		'role_name'
	];

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class, 'role_name');
	}

	public function dosurveys()
	{
		return $this->hasMany(\App\Models\Dosurvey::class);
	}

	public function courses()
	{
		return $this->belongsToMany(\App\Models\Course::class, 'studentcourse')
					->withPivot('id');
	}

    public function hasPermission($per) 
    {
        $tmp = $this->role()->where('permission', $per)->get()->toArray();
        if (empty($tmp))
            return false;
        return true;
    }
}
