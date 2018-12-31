<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 30 Dec 2018 10:16:03 +0000.
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

/**
 * Class Admin
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $role_name
 * 
 * @property \App\Models\Role $role
 *
 * @package App\Models
 */
class Admin extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;
	protected $table = 'admin';
	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'username',
		'password',
		'role_name'
	];

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class, 'role_name');
	}
}
