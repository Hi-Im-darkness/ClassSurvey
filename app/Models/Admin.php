<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
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
 * @property string $remember_token
 *
 * @package App\Models
 */
class Admin extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;
	protected $table = 'admin';
	public $timestamps = false;

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'username',
		'password',
	];
}
