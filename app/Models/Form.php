<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Form
 * 
 * @property int $id
 * @property string $name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $questions
 * @property \Illuminate\Database\Eloquent\Collection $surveys
 *
 * @package App\Models
 */
class Form extends Eloquent
{
	protected $table = 'form';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function questions()
	{
		return $this->belongsToMany(\App\Models\Question::class, 'formquestion')
					->withPivot('id');
	}

	public function surveys()
	{
		return $this->hasMany(\App\Models\Survey::class);
	}
}
