<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Admin
 * 
 * @property int $id
 * @property string $name
 * @property string $user_name
 * @property string $password
 * @property string $remember_token
 *
 * @package App\Models
 */
class Admin extends Eloquent
{
	protected $table = 'admin';
	public $timestamps = false;

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'user_name',
		'password',
	];
}
