<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Question
 * 
 * @property int $id
 * @property string $content
 * 
 * @property \Illuminate\Database\Eloquent\Collection $dosurveys
 * @property \Illuminate\Database\Eloquent\Collection $forms
 *
 * @package App\Models
 */
class Question extends Eloquent
{
	protected $table = 'question';
	public $timestamps = false;
    protected $hidden = ['pivot'];

	protected $fillable = [
		'content'
	];

	public function dosurveys()
	{
		return $this->hasMany(\App\Models\Dosurvey::class);
	}

	public function forms()
	{
		return $this->belongsToMany(\App\Models\Form::class, 'formquestion')
					->withPivot('id');
	}
}
